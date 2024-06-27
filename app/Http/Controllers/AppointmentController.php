<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Order;
use App\Models\Record;
use App\Models\Status;
use App\Models\Type;
use App\Traits\Helper;
use App\Traits\ReturnResponse;
use Egulias\EmailValidator\Warning\ObsoleteDTEXT;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AppointmentController extends Controller
{
    use ReturnResponse;
    use Helper;

    public function index()
    {
        $appointment = Appointment::query()->select(['*'])
            ->with(['user' => function ($query) {
                    $query->select(['id', 'name', 'email', 'phone', 'uId']);
                }]
            )
            ->get();

        if ($appointment) {
            return $this->returnData('appointment', $appointment);
        } else {

            return $this->returnError(404, 'appointment not found');
        }
    }

    public function store(Request $request)
    {
        $orderId = $request['orderId'];
        $order = Order::find($orderId);
        if (!$order) {
            return $this->returnError(404, 'order not found');
        }
        if ($order->state == 'waiting') {
            $appointment = new Appointment();
            $appointment->create([
                'start_time' => $request['start_time'],
                'team_id' => $request['team_id'],
                'order_id' => $orderId,
                'user_id' => $order->user_id,
                'type_id' => 1,
                'status_id' => 2
            ]);
            $order->state = 'Detect';
            $order->save();
            return $this->returnSuccessMessage('appointment accepted successfully');
        } else {
            return $this->returnError(400, 'order cannot be accepted');
        }
    }

    public function update(Request $request, $appId)
    {
        $input = $request->all();
        $appointment = Appointment::find($appId);

        if (!$appointment) {
            return $this->returnError(404, 'Appointment not found');
        }

        $validator = Validator::make($input, [
            'products' => 'required|array',
            'products.*.id' => 'required|exists:products,id',
            'products.*.amount' => 'required|numeric|between:0,99.99',
        ]);

        if ($validator->fails()) {
            return $this->returnError(422, $validator->errors());
        }

        $order = $appointment->order;

        if (!$order) {
            return $this->returnError(404, 'Order not found');
        }


        $products = [];
        foreach ($input['products'] as $product) {
            $products[$product['id']] = ['amount' => $product['amount']];
        }

        $order->products()->sync($products);

        $order->state = 'Execute';
        $order->save();

        return $this->returnSuccessMessage('Order updated successfully');
    }

    public function teamApp($teamID)
    {
        $apps = Appointment::query()->where('team_id', '=', $teamID)
            ->with('user')->get();
        return $this->returnData('Appointments:', $apps);
    }

    public function done(Request $request, $appId)
    {
        $appointment = Appointment::find($appId);

        if ($appointment->type_id == 1) {
            $input = $request->all();
            $validator = Validator::make($input, [
                'desc' => 'required|string',
            ]);

            if ($validator->fails()) {
                return $this->returnError(422, $validator->errors());
            }

            $order = Order::find($appointment->order_id);
            $record = Record::find($appointment->order_id);
            $record->desc = $input['desc'];
            $record->save();
            $order->state = 'Done';
            $order->save();
        } elseif ($appointment->type_id == 2) {
            $order = Order::find($appointment->order_id);
            $order->state = 'Done';
            $order->save();
        }

        return $this->returnSuccessMessage('Order updated successfully');
    }
    
    public function getType()
    {
        $type = Type::all();
        return $this->returnData('types : ', $type);
    }

    public function getStatus()
    {
        $status = Status::all();
        return $this->returnData('statuses : ', $status);
    }
}
