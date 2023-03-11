<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\Request;
use Validator;

class ProductController extends Controller
{
     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products = Product::latest()->paginate(12);

        if ($products->count() > 0) {
            return response([
                'success' => true,
                'data'    => ProductResource::collection($products)
            ]);
        }

        return response([
            'success' => false,
            'data'    => [],
            'message' => 'Sorry, Data not found!!',
        ]);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $input = $request->all();
   
        $validator = Validator::make($input, [
            'name'          => 'required',
            'price'         => 'required',
            'description'   => 'required'
        ]);
   
        if ($validator->fails()) {
            return response([
                'success' => false,
                'errors'    => $validator->errors(),
                'message' => 'Validation Error',
            ]);
        }
   
        $product = Product::create($input);

        return response([
            'success' => true,
            'data'    => new ProductResource($product),
            'message' => 'Product Created Successfully.',
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $product = Product::find($id);
  
        if (is_null($product)) {
            return $this->sendError('Product not found.');
        }
   
        return $this->sendResponse(new ProductResource($product), 'Product Retrieved Successfully.');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {  
        $input = $request->all();
   
        $validator = Validator::make($input, [
            'name'          => 'required',
            'price'         => 'required',
            'description'   => 'required'
        ]);
   
        if($validator->fails()){
            return response([
                'success' => false,
                'errors'    => $validator->errors(),
                'message' => 'Validation Error',
            ]);      
        }

        $product = Product::find($id);   
        $product->update($input);
   
        return response([
            'success' => true,
            'data'    => new ProductResource($product),
            'message' => 'Product Updated Successfully.',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $product = Product::find($id);
        $product->delete();
   
        return response([
            'success' => true,
            'message' => 'Product Deleted Successfully',
        ]);   
    }
}
