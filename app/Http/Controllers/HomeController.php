<?php

namespace App\Http\Controllers;

use App\Http\Resources\CategoryBrowseResource;
use App\Http\Resources\ProductBrowseResource;
use App\Http\Resources\StoreBrowseResource;
use App\Http\Resources\StoreProductBrowseResource;
use App\Models\Category;
use App\Models\Product;
use App\Models\Store;
use App\Models\StoreProduct;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class HomeController extends Controller
{
    public function search(Request $request)
    {
        $data = $request->validate([
            'query' => ['required', 'string'],
            'categoryId' => ['nullable', 'integer', 'exists:categories,id']
        ]);

        $query = Str::lower($data['query']);

        $stores = new Collection();

        $categories = new Collection();

        if (!$data['categoryId']) {
            /** @var Builder $products */
            $products = StoreProduct::select('*');

            $stores = StoreBrowseResource::collection(
                Store::whereRaw("lower(name) like '%{$query}%'")
                    ->limit(10)
                    ->get()
            );

            $categories = CategoryBrowseResource::collection(
                Category::whereRaw("lower(name) like '%{$query}%'")
                    ->orWhereRaw("lower(name_en) like '%{$query}%'")
                    ->limit(10)
                    ->get()
            );
        } else {
            $products = Category::find($data['categoryId'])->products();
        }

        $products->whereRaw("lower(title) like '%{$query}%'");

        return ProductBrowseResource::collection($products->paginate(30))->additional([
            'stores' => $stores,
            'categories' => $categories
        ]);
    }

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
            ->orderByDesc('views')
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
