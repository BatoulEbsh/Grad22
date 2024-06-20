<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Traits\ReturnResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    use ReturnResponse;

    public function index()
    {
        $products = Product::query()->where('available', '=', 1)
            ->select(['*'])->get();
        if ($products) {
            return $this->returnData('products', $products);
        } else {

            return $this->returnError(404, 'Job not found');
        }

    }

    public function showAll()
    {
        $products = Product::query()->select(['*'])->get();
        if ($products) {
            return $this->returnData('products', $products);
        } else {

            return $this->returnError(404, 'Job not found');
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'price' => 'required|numeric',
            'disc' => 'required|string',
            'quantity' => 'required|numeric',
            'image' => 'image|max:2400',
            'category_id' => 'required|exists:categories,id'
        ]);
        if ($validator->fails()) {
            return $this->returnError(422, $validator->errors());
        }
        $newImage = time() . $request['image']->getClientOriginalName();
        $path = $request['image']->move("images/", $newImage);

        $product = new Product();
        $product->fill([
            'name' => $request['name'],
            'price' => $request['price'],
            'disc' => $request['disc'],
            'quantity' => $request['quantity'],
            'image' => $path,
            'category_id' => $request['category_id']
        ]);
        $product->save();
        return $this->returnSuccessMessage('product added successfully');
    }

    public function show($id)
    {
        $products = Product::find($id)->where('id', '=', $id)->get();
        if ($products) {
            return $this->returnData('products', $products);
        } else {

            return $this->returnError(404, 'product not found');
        }

    }

    public function search($id)
    {
        $products = Product::query()
            ->where('category_id', '=', $id)
            ->get();
        if ($products) {
            return $this->returnData('products', $products);
        } else {

            return $this->returnError(404, 'product not found');
        }
    }

    public function updateProductAvailable($id)
    {
        $product = Product::find($id);
        if (!$product->available) {
            $product->update(['available' => true]);
        } else
            $product->update(['available' => false]);
        return $this->returnSuccessMessage('state updated successfully');
    }

}
