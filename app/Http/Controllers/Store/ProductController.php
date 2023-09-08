<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use App\Http\Resources\ReviewBrowseResource;
use App\Http\Resources\StoreProductBrowseResource;
use App\Http\Resources\StoreProductResource;
use App\Models\Product;
use App\Models\Review;
use App\Models\ReviewReaction;
use App\Models\Store;
use App\Models\StoreCategory;
use App\Models\StoreProduct;
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
            'perPage' => ['nullable', 'integer', 'max:100']
        ]);

        $perPage = $request->get('perPage', 25);

        $categoryId = $request->get('categoryId');

        if ($categoryId) {
            /** @var StoreCategory $category */
            $category = StoreCategory::find($request->get('categoryId'));
            $products = $category->products();
        } else {
            $products = $store->products();
        }

        return StoreProductBrowseResource::collection($products->paginate($perPage));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
