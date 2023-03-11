<?php

namespace App\Http\Controllers\Api\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\Request;

class HomeController extends Controller
{
     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function products()
    {
        $products = Product::all();

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
}
