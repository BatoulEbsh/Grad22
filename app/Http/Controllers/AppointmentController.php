<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Order;
use App\Models\Status;
use App\Models\Type;
use App\Traits\Helper;
use App\Traits\ReturnResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AppointmentController extends Controller
{
    use ReturnResponse;
    use Helper;

    public function index()
    {
        $appointment = Appointment::query()->select(['*'])->get();
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
                'type_id' => 1 ,
                'status_id' => 2
            ]);
            $order->state = 'Detect';
            $order->save();
            return $this->returnSuccessMessage('appointment accepted successfully');
        } else {
            return $this->returnError(400, 'order cannot be accepted');
        }
    }
    public function update(Request $request, $id){
        $appointment = Appointment::find($id);
        $validator = Validator::make($request->all(), [
            'products' => 'required|array',
            'products.*' => 'exists:products,id',
            'end_time'=>'required|date'
        ]);
        if ($validator->fails()) {
            return $this->returnError(422, $validator->errors());
        }
        $appointment->fill([
            'end_time' => $request['end_time']
        ]);

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
