<?php

namespace App\Http\Controllers;

use App\Models\Invoices;
use App\Models\Order;
use App\Traits\Helper;
use App\Traits\ReturnResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class InvoicesController extends Controller
{
    use ReturnResponse;
    use Helper;

    public function index()
    {
        $invoices = Invoices::with('order')->get();
        return $this->returnData('invoices', $invoices);
    }

    public function show($id)
    {
        $invoice = Invoices::with('order')->find($id);
        if (!$invoice) {
            return $this->returnError(404, 'Invoice not found');
        }
        return $this->returnData('invoice', $invoice);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'order_id' => 'required|exists:orders,id',
            'otherPrice' => 'required|numeric|between:0,99.99'
        ]);

        if ($validator->fails()) {
            return $this->returnError(422, $validator->errors());
        }
        $invoice = new Invoices();
        $invoice->fill([
            'order_id' => $request['order_id'],
            'date' => now(),
            'otherPrice' => $request['otherPrice'],
            'totalPrice' => $this->calculateTotalPrice($request['order_id']) + $request['otherPrice'],
        ]);
        $invoice->save();
        return $this->returnSuccessMessage('invoice added successfully');

    }
}
