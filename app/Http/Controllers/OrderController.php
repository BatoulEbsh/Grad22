<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Order;
use App\Models\ProposedSystem;
use App\Models\Type;
use App\Traits\Helper;
use App\Traits\ReturnResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{
    use ReturnResponse;
    use Helper;

    public function index()
    {
        $orders = Order::query()
            ->where('state', '=', 'waiting')
            ->with(['user', 'products'])
            ->get();

        if ($orders->isNotEmpty()) {
            return $this->returnData('orders', $orders);
        } else {
            return $this->returnError(404, 'order not found');
        }

    }

    public function store(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'type_id' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return $this->returnError(422, $validator->errors());
        }

        if ($input['type_id'] == 1) {
            $validator = Validator::make($input, [
                'image' => 'image|max:2400',
                'desc' => 'required|string',
            ]);

            if ($validator->fails()) {
                return $this->returnError(422, $validator->errors());
            }

            $newImage = time() . $request['image']->getClientOriginalName();
            $path = $request['image']->move("images/", $newImage);

            $order = new Order();
            $order->fill([
                'image' => $path,
                'desc' => $input['desc'],
                'type_id' => $input['type_id'],
                'user_id' => Auth::id(),
            ]);
            $order->save();

            return $this->returnSuccessMessage('order added successfully');
        } elseif ($input['type_id'] == 2) {
            $validator = Validator::make($input, [
                'products' => 'required|array',
                'products.*.id' => 'required|exists:products,id',
                'products.*.amount' => 'required|numeric|between:0,99.99',
                'location' => 'required|string',
            ]);

            if ($validator->fails()) {
                return $this->returnError(422, $validator->errors());
            }

            $order = new Order();
            $order->fill([
                'location' => $input['location'],
                'type_id' => $input['type_id'],
                'user_id' => Auth::id(),
            ]);
            $order->save();

            $productsWithAmount = [];
            foreach ($input['products'] as $product) {
                $productsWithAmount[$product['id']] = ['amount' => $product['amount']];
            }
            $order->products()->attach($productsWithAmount);

            return $this->returnSuccessMessage('order added successfully');
        }
    }

    public function showAllMyOrder()
    {
        $userId = Auth::id();
        $orders = Order::where('user_id', $userId)->get();

        if ($orders->isEmpty()) {
            return $this->returnError(404, 'order not found');
        }

        $ordersWithAppointments = [];
        foreach ($orders as $order) {
            if ($order->state == 'Detect') {
                $appointment = Appointment::where('order_id', $order->id)
                    ->select(['start_time'])->get();
                $ordersWithAppointments[] = [
                    'order' => $order,
                    'appointment' => $appointment
                ];
            } else {
                $ordersWithAppointments[] = ['order' => $order];
            }
        }

        return $this->returnData('orders', $ordersWithAppointments);
    }


    public function reject($id)
    {
        $order = Order::find($id);
        if (!$order) {
            return $this->returnError(404, 'order not found');
        }
        if ($order->state == 'waiting') {
            $order->state = 'rejected';
            $order->save();
            return $this->returnSuccessMessage('order rejected successfully');
        } else {
            return $this->returnError(400, 'order cannot be rejected');
        }
    }
}
