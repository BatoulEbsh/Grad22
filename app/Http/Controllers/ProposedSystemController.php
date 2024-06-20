<?php

namespace App\Http\Controllers;

use App\Models\ProposedSystem;
use App\Traits\ReturnResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProposedSystemController extends Controller
{
    use ReturnResponse;

    public function index()
    {
        $systems = ProposedSystem::query()->select(['*'])->with('products')
            ->get();
        if ($systems) {
            return $this->returnData('systems', $systems);
        } else {

            return $this->returnError(404, 'system not found');
        }


    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'desc' => 'nullable|string',
            'products' => 'required|array',
            'products.*' => 'exists:products,id',
        ]);

        if ($validator->fails()) {
            return $this->returnError(422, $validator->errors());
        }

        $system = ProposedSystem::create([
            'name' => $request->input('name'),
            'desc' => $request->input('desc'),
        ]);


        $system->products()->attach($request->input('products'));

        return $this->returnSuccessMessage('System added successfully');
    }
}
