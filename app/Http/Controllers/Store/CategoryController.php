<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryBrowseResource;
use App\Http\Resources\FieldResource;
use App\Http\Resources\FileBrowseResource;
use App\Http\Resources\StoreCategoryBrowseResource;
use App\Http\Resources\StoreCategoryEditResource;
use App\Http\Resources\StoreCategoryFieldResource;
use App\Http\Resources\StoreCategoryResource;
use App\Models\Field;
use App\Models\Store;
use App\Models\StoreCategory;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(Store $store)
    {
        return StoreCategoryEditResource::collection($store->parentCategories);
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
     * @return StoreCategoryEditResource
     */
    public function store(Request $request, $store)
    {
        $request->validate([
            'name' => ['required', 'string'],
            'parentId' => ['nullable', 'integer', 'exists:store_categories,id'],
            'active' => ['required', 'boolean'],
            'hasColor' => ['required', 'boolean'],
            'sizeFieldId' => ['nullable', 'integer', 'exists:fields,id'],
            'maxPrice' => ['nullable', 'numeric'],
            'matchCategoryId' => ['required', 'integer', 'exists:categories,id'],
            'icon' => ['nullable', 'array'],
            'icon.id' => ['nullable', 'integer', 'exists:files,id'],
            'icon.file' => ['nullable', 'file']
        ]);

        $request->request->set('storeId', $store);

        $cat = StoreCategory::create($request->all());

        return new StoreCategoryEditResource($cat);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function sort(Request $request)
    {
        $request->validate([
            'sort' => ['required', 'array'],
            'sort.*.id' => ['required', 'integer', 'exists:store_categories,id'],
            'sort.*.parentId' => ['nullable', 'integer', 'exists:store_categories,id']
        ]);

        $sort = $request->get('sort');
        for ($i = 0; $i < count($sort); $i++) {
            $cat = StoreCategory::find($sort[$i]['id']);
            $cat->fill([
                'parentId' => @$sort[$i]['parentId'],
                'order' => $i
            ])->save();
        }
        return response()->json(['message' => 'success']);

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\StoreCategory  $storeCategory
     * @return StoreCategoryResource
     */
    public function show($store, StoreCategory $storeCategory)
    {
        return new StoreCategoryResource($storeCategory);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\StoreCategory  $storeCategory
     * @return \Illuminate\Http\JsonResponse
     */
    public function edit($store, StoreCategory $storeCategory)
    {
        return response()->json([
            'id' => $storeCategory->id,
            'fields' => FieldResource::collection($storeCategory->fields),
            'icon' => $storeCategory->icon ? new FileBrowseResource($storeCategory->icon) : null,
            'matchCategory' => new CategoryBrowseResource($storeCategory->matchCategory),
            'maxPrice' => $storeCategory->maxPrice,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param $store
     * @param \App\Models\StoreCategory $storeCategory
     * @return StoreCategoryEditResource
     */
    public function update(Request $request, $store, StoreCategory $storeCategory)
    {
        $request->validate([
            'name' => ['required', 'string'],
            'parentId' => ['nullable', 'integer', 'exists:store_categories,id'],
            'active' => ['required', 'boolean'],
            'hasColor' => ['required', 'boolean'],
            'sizeFieldId' => ['nullable', 'integer', 'exists:fields,id'],
            'maxPrice' => ['nullable', 'numeric'],
            'matchCategoryId' => ['required', 'integer', 'exists:categories,id'],
            'icon' => ['nullable', 'array'],
            'icon.id' => ['nullable', 'integer', 'exists:files,id'],
            'icon.file' => ['nullable', 'file']
        ]);

        $storeCategory->fill($request->all())->save();

        return new StoreCategoryEditResource($storeCategory);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\StoreCategory  $storeCategory
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($store, StoreCategory $storeCategory)
    {
        if ($storeCategory->storeId == $store)
            $storeCategory->delete();
        return response()->json($storeCategory->toJson());
    }

    public function fields($store, StoreCategory $storeCategory)
    {
        return $storeCategory->fields->map(function (Field $field) {
            return ['id' => $field->id, 'name' => $field->name];
        });
    }
}
