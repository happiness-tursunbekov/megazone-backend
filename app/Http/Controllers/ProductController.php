<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProductBrowseResource;
use App\Models\Category;
use App\Models\Product;
use App\Models\StoreProduct;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $request->validate([
            'categoryId' => ['nullable', 'integer', 'exists:categories,id'],
            'perPage' => ['nullable', 'integer', 'max:100'],
            'brandIds' => ['nullable', 'array'],
            'brandIds.*' => ['nullable', 'integer', 'exists:brands,id'],
            'sizeIds' => ['nullable', 'array'],
            'colorId' => ['nullable', 'integer'],
            'priceFrom' => ['nullable', 'numeric'],
            'priceTo' => ['nullable', 'numeric'],
        ]);

        $perPage = $request->get('perPage', 25);

        $categoryId = $request->get('categoryId');

        $brandIds = $request->get('brandIds');
        $sizeIds = $request->get('sizeIds');
        $colorId = $request->get('colorId');
        $priceFrom = $request->get('priceFrom');
        $priceTo = $request->get('priceTo');

        if ($categoryId) {
            /** @var Category $category */
            $category = Category::find($request->get('categoryId'));
            $products = $category->products();
        } else {
            $products = StoreProduct::select('*');
        }

        if ($brandIds && count($brandIds) > 0)
            $products->whereIn('brand_id', $brandIds);

        if ($priceFrom)
            $products->where('price', '>=', $priceFrom);

        if ($priceTo)
            $products->where('price', '<=', $priceTo);

        if ($colorId)
            $products->where('color_id', $colorId);

        if ($sizeIds && count($sizeIds) > 0)
            $products->join('store_product_size', 'store_products.id', '=', 'store_product_size.store_product_id')
                ->whereIn('store_product_size.size_id', $sizeIds);

        return ProductBrowseResource::collection($products->paginate($perPage));
    }
}
