<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use App\Http\Resources\StoreBrowseResource;
use App\Http\Resources\StoreResource;
use App\Models\Address;
use App\Models\Store;
use App\Models\StoreType;
use App\Services\FileService;
use Illuminate\Http\Request;

class StoreController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        return StoreBrowseResource::collection(Store::active()->paginate(20));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function create()
    {
        return response()->json([
            'types' => StoreType::all()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return StoreResource
     */
    public function store(Request $request, FileService $fileService)
    {
        $data = $request->validate([
            'name' => ['required', 'string'],
            'slug' => ['required', 'string', 'unique:stores,slug'],
            'slogan' => ['nullable', 'string'],
            'about' => ['nullable', 'string'],
            'icon' => ['nullable', 'string'],
            'cover' => ['nullable', 'string'],
            'address' => ['required', 'array'],
            'address.lat' => ['required', 'numeric'],
            'address.lng' => ['required', 'numeric'],
            'address.fullPath' => ['required', 'string'],
            'storeTypeIds' => ['required', 'array', 'min:1'],
            'storeTypeIds.*' => ['required', 'integer', 'exists:store_types,id']
        ]);

        $data['active'] = true;

        if ($data['icon']) {
            $data['iconId'] = $fileService->saveBase64File($data['icon'])->id;
        }

        if ($data['cover']) {
            $data['coverId'] = $fileService->saveBase64File($data['cover'])->id;
        }

        $data['addressId'] = Address::create($data['address'])->id;
        /** @var Store $store */
        $store = Store::create($data);

        $store->types()->sync($data['storeTypeIds']);

        return new StoreResource($store);
    }

    /**
     * Display the specified resource.
     *
     * @param string $slug
     * @return StoreResource
     */
    public function show(string $slug)
    {
        $store = Store::findBySlug($slug);

        if (!$store)
            abort(404);

        return new StoreResource($store);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Store  $store
     * @return \Illuminate\Http\JsonResponse
     */
    public function edit(Store $store)
    {
        return response()->json([
            'item' => $store->toJson(),
            'types' => StoreType::all()
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Store  $store
     * @return StoreResource
     */
    public function update(Request $request, Store $store, FileService $fileService)
    {
        $data = $request->validate([
            'name' => ['required', 'string'],
            'slug' => ['required', 'string', 'unique:stores,slug,' . $store->id],
            'slogan' => ['nullable', 'string'],
            'about' => ['nullable', 'string'],
            'icon' => ['nullable', 'string'],
            'cover' => ['nullable', 'string'],
            'address' => ['required', 'array'],
            'address.lat' => ['required', 'numeric'],
            'address.lng' => ['required', 'numeric'],
            'address.fullPath' => ['required', 'string'],
            'storeTypeIds' => ['required', 'array', 'min:1'],
            'storeTypeIds.*' => ['required', 'integer', 'exists:store_types,id']
        ]);

        if ($data['icon']) {
            $data['iconId'] = $fileService->saveBase64File($data['icon'])->id;
        }

        if ($data['cover']) {
            $data['coverId'] = $fileService->saveBase64File($data['cover'])->id;
        }

        if ($store->addressId) {
            if (!$store->address->fill($data['address'])->save())
                abort(500);
            else
                $data['addressId'] = Address::create($data['address'])->id;
        }

        if (!$store->fill($data)->save())
            abort(500);

        $store->types()->sync($data['storeTypeIds']);

        return new StoreResource($store);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Store  $store
     * @return \Illuminate\Http\Response
     */
    public function destroy(Store $store)
    {
        //
    }
}
