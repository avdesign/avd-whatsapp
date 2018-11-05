<?php

namespace CodeShopping\Http\Controllers\Api;

use CodeShopping\Http\Resources\ProductResource;
use CodeShopping\Http\Requests\ProductRequest;
use CodeShopping\Http\Controllers\Controller;
use CodeShopping\Common\OnlyTrashed;
use CodeShopping\Models\Product;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    use OnlyTrashed;

    public function index(Request $request)
    {
        $query = Product::query();
        $query = $this->onlyTrashedIfRequested($request, $query);
        $products = $query->paginate(10);
        return ProductResource::collection($products);
    }

    public function store(ProductRequest $request)
    {
        $product = Product::create($request->all());
        $product->refresh();
        return new ProductResource($product);
    }

    public function show(Product $product)
    {
        return new ProductResource($product);
    }

    public function update(ProductRequest $request, Product $product)
    {
        $product->fill($request->all());
        $product->save();
        
        return new ProductResource($product);
        //return response()->json([], 204);
    }

    /**
     * @param Product $product
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Product $product)
    {
        $product->delete();

        return response()->json([], 204);
    }

    /**
     * @param Product $product
     * @return \Illuminate\Http\JsonResponse
     */
    public function restore(Product $product)
    {
        $product->restore();
        return response()->json([], 204);
    }


}
