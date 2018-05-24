<?php

namespace App\Http\Controllers;

use App\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function create (Request $request)
    {
        $product = Product::create([
            'name' => htmlspecialchars($request->name),
            'description' => htmlspecialchars($request->description),
            'price' => $request->price,
            'category_id' => $request->category_id
        ]);

        return response()->json(['product' => $product]);
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function update (Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $product->name = $request->name;
        $product->description = $request->description;
        $product->price = $request->price;
        $product->category_id = $request->category_id;

        if($product->save())
            return response()->json(['product' => $product]);
        else
            throw new \Exception('Error when update...', 500);
    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete ($id)
    {
        $product = Product::findOrFail($id);

        if($product->delete())
            return response()->json(true);
        else
            return response()->json(false, 500);
    }
}
