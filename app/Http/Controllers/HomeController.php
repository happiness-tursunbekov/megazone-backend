<?php

namespace App\Http\Controllers;

use App\Http\Resources\CategoryBrowseResource;
use App\Http\Resources\ProductBrowseResource;
use App\Http\Resources\StoreProductBrowseResource;
use App\Models\Category;
use App\Models\StoreProduct;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        return response()->json([
            'popularCategories' => CategoryBrowseResource::collection($this->getPopularCategories()),
            'newProducts' => ProductBrowseResource::collection($this->getLastAddedProducts()),
            'categoryNewProducts' => $this->getLastAddedProductsByCategories()
        ]);
    }

    private function getPopularCategories()
    {
        return Category::select('categories.*')
            ->join('categories as pr', 'categories.parent_id', '=', 'pr.id')
            ->whereNull('pr.parent_id')
            ->limit(6)
            ->get();
    }

    private function getLastAddedProducts()
    {
        return StoreProduct::orderBy('created_at')->limit(12)->get();
    }
    private function getLastAddedProductsByCategories()
    {
        return Category::whereNull('parent_id')->get()->map(function (Category $category) {
            return [
                'id' => $category->id,
                'name' => $category->nameTranslated,
                'products' => ProductBrowseResource::collection($category->products()->orderBy('created_at')->limit(6)->get())
            ];
        });
    }
}
