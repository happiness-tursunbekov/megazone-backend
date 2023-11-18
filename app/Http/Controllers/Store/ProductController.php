<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use App\Http\Resources\BrandBrowseResource;
use App\Http\Resources\CurrencyBrowseResource;
use App\Http\Resources\CurrencyTypeBrowseResource;
use App\Http\Resources\ReviewBrowseResource;
use App\Http\Resources\StoreCategoryFieldGroupResource;
use App\Http\Resources\StoreCategoryFieldResource;
use App\Http\Resources\StoreProductBrowseResource;
use App\Http\Resources\StoreProductResource;
use App\Models\Brand;
use App\Models\BrandModel;
use App\Models\Country;
use App\Models\Currency;
use App\Models\CurrencyType;
use App\Models\Field;
use App\Models\Product;
use App\Models\Review;
use App\Models\ReviewReaction;
use App\Models\Store;
use App\Models\StoreCategory;
use App\Models\StoreProduct;
use App\Services\FileService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(Store $store, Request $request)
    {
        $request->validate([
            'categoryId' => ['nullable', 'integer', 'exists:store_categories,id'],
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
            /** @var StoreCategory $category */
            $category = StoreCategory::find($request->get('categoryId'));
            $products = $category->products();
        } else {
            $products = $store->products();
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

        return StoreProductBrowseResource::collection($products->paginate($perPage));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(Request $request)
    {
        $request->validate([
            'storeCategoryId' => ['required', 'integer', 'exists:store_categories,id']
        ]);

        /** @var StoreCategory $category */
        $category = StoreCategory::find($request->get('storeCategoryId'));
        return response()->json([
            'fields' => StoreCategoryFieldResource::collection($category->fields),
            'groups' => StoreCategoryFieldGroupResource::collection($category->groups),
            'brands' => BrandBrowseResource::collection($category->brands),
            'currencyTypes' => CurrencyTypeBrowseResource::collection(CurrencyType::all())
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function store(Store $store, Request $request, FileService $fileService)
    {
        $data = $request->validate([
            'brandId' => ['required', 'integer', 'exists:brands,id'],
            'modelId' => ['required', 'integer', 'exists:brand_models,id'],
            'price' => ['required', 'numeric', 'min:0'],
            'currencyTypeId' => ['required', 'integer', 'exists:currency_types,id'],
            'storeCategoryId' => ['required', 'integer', 'exists:store_categories,id'],
            'title' => ['required', 'string'],
            'description' => ['nullable', 'string'],
            'fields' => ['nullable', 'array'],
            'fields.*' => ['nullable'],
            'groups' => ['nullable', 'array'],
            'groups.*.fields' => ['nullable', 'array'],
            'groups.*.fields.*' => ['nullable'],
            'files' => ['required', 'array', 'min:1']
        ]);

        $product = Product::findByBrandModel($data['brandId'], $data['modelId']);

        if (!$product) {
            /** @var StoreCategory $storeCategory */
            $storeCategory = StoreCategory::find($data['storeCategoryId']);

            $brand = Brand::find($data['brandId']);
            $model = BrandModel::find($data['modelId']);

            /** @var Product $product */
            $product = Product::create([
                'title' => $brand->name . ' ' . $model->title,
                'brandId' => $brand->id,
                'modelId' => $model->id,
                'categoryId' => $storeCategory->matchCategoryId
            ]);

            $product->handleCategoryRelations();
        }

        /** @var StoreProduct $storeProduct */
        $storeProduct = StoreProduct::create([
            'title' => $data['title'],
            'description' => $data['description'],
            'productId' => $product->id,
            'storeId' => $store->id,
            'brandId' => $product->brandId,
            'modelId' => $product->modelId,
            'price' => $data['price'],
            'currencyTypeId' => $data['currencyTypeId'],
            'countryId' => 117,
            'storeCategoryId' => $data['storeCategoryId']
        ]);

        $storeProduct->handleCategoryRelations();

        foreach ($data['files'] as $fileBase64) {
            $file = $fileService->saveBase64File($fileBase64);
            $storeProduct->files()->attach($file->id);
        }

        if (is_array($data['fields'])) {
            foreach ($data['fields'] as $fieldId => $value) {
                /** @var Field $field */
                $field = Field::find($fieldId);
                $storeProduct->saveField($field, $value);
            }
        }

        if (is_array($data['groups'])) {
            foreach ($data['groups'] as $fields)
                foreach ($fields as $fieldId => $value) {
                    /** @var Field $field */
                    $field = Field::find($fieldId);
                    $storeProduct->saveField($field, $value);
                }
        }

        return $storeProduct->toJson();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\StoreProduct  $storeProduct
     * @return StoreProductResource
     */
    public function show($store, StoreProduct $storeProduct)
    {
        return new StoreProductResource($storeProduct);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\StoreProduct  $storeProduct
     * @return \Illuminate\Http\Response
     */
    public function edit(StoreProduct $storeProduct)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\StoreProduct  $storeProduct
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, StoreProduct $storeProduct)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\StoreProduct  $storeProduct
     * @return \Illuminate\Http\Response
     */
    public function destroy(StoreProduct $storeProduct)
    {
        //
    }

    public function reviews($store, StoreProduct $storeProduct)
    {
        return ReviewBrowseResource::collection($storeProduct->reviews()->paginate(5));
    }

    public function storeReview($store, StoreProduct $storeProduct, Request $request)
    {
        $request->validate([
            'name' => ['required', 'string'],
            'message' => ['required', 'string'],
            'rating' => ['required', 'integer', 'max:5', 'min:1']
        ]);

        $userId = $request->user()->id;

        if ($review = $storeProduct->reviews()->where('user_id', '=', $userId)->first())
            $review->fill([
                'name' => $request->get('name'),
                'message' => $request->get('message'),
                'rating' => $request->get('rating'),
            ])->save();
        else
            $review = Review::create([
                'name' => $request->get('name'),
                'message' => $request->get('message'),
                'userId' => $userId,
                'rating' => $request->get('rating'),
                'modelType' => StoreProduct::class,
                'modelId' => $storeProduct->id
            ]);

        return new ReviewBrowseResource($review);
    }

    public function reviewReaction($store, $storeProduct, Review $review, Request $request)
    {
        $request->validate([
            'status' => ['required', 'boolean']
        ]);

        $ip = $request->ip();

        if ($reaction = $review->reactions()->where('ip', '=', $ip)->first())
            $reaction->fill(['helpful' => $request->get('status')])->save();
        else
            ReviewReaction::create([
                'reviewId' => $review->id,
                'helpful' => $request->get('status'),
                'ip' => $ip
            ]);
        return response()->json([
            'helpful' => $review->helpful,
            'unhelpful' => $review->unhelpful
        ]);
    }
}
