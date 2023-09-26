<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use App\Http\Resources\BrandModelBrowseResource;
use App\Http\Resources\CategoryBrowseResource;
use App\Http\Resources\FieldResource;
use App\Http\Resources\FileBrowseResource;
use App\Http\Resources\StoreCategoryEditResource;
use App\Http\Resources\StoreCategoryFieldEditResource;
use App\Http\Resources\StoreCategoryFieldGroupEditResource;
use App\Http\Resources\StoreCategoryResource;
use App\Models\Field;
use App\Models\Option;
use App\Models\Store;
use App\Models\StoreCategory;
use App\Models\StoreCategoryFieldGroup;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

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
        return StoreCategoryFieldEditResource::collection($storeCategory->fields);
    }

    public function groups($store, StoreCategory $storeCategory)
    {
        return StoreCategoryFieldGroupEditResource::collection($storeCategory->groups);
    }

    public function groupStore($store, StoreCategory $storeCategory, Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string'],
            'nameEn' => ['required', 'string'],
        ]);

        $data['storeCategoryId'] = $storeCategory->id;

        $group = StoreCategoryFieldGroup::create($data);

        return new StoreCategoryFieldGroupEditResource($group);
    }

    public function fieldStore($store, StoreCategory $storeCategory, Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string'],
            'nameEn' => ['required', 'string'],
            'type' => ['required', 'string'],
            'filter' => ['required', 'boolean'],
            'required' => ['required', 'boolean'],
            'addon' => ['nullable', 'string'],
            'storeCategoryFieldGroupId' => ['nullable', 'exists:store_category_field_groups,id'],
            'fieldId' => ['nullable', 'exists:fields,id'],
            'options' => ['nullable', 'array'],
            'options.*.title' => ['required_with:options', 'string'],
            'options.*.titleEn' => ['required_with:options', 'string'],
        ]);

        if (!$data['fieldId']) {
            /** @var Field $field */
            $field = Field::create([
                'name' => $data['name'],
                'nameEn' => $data['nameEn'],
                'type' => $data['type'],
                'code' => Str::slug($data['nameEn']),
                'addon' => $data['addon']
            ]);
        } else {
            $field = Field::find($data['fieldId']);
        }

        if ($data['options'] && count($data['options']) > 0) {
            foreach ($data['options'] as $optionData) {
                $option = Option::create($optionData);
                $field->options()->attach($option->id);
            }
        }

        $field->options()->attach();

        $storeCategory->fields()->attach($field->id, [
            'store_category_field_group_id' => $data['storeCategoryFieldGroupId'],
            'required' => $data['required'],
            'filter' => $data['filter']
        ]);

        return new StoreCategoryFieldEditResource($field);
    }

    public function brandModels($store, StoreCategory $storeCategory, Request $request)
    {
        $request->validate([
            'brandId' => ['required', 'integer', 'exists:brands,id']
        ]);

        $models = $storeCategory->brandModelsByBrandId($request->get('brandId'));

        return BrandModelBrowseResource::collection($models);
    }
}
