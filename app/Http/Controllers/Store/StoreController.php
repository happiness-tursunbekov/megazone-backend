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
     * @return \Illuminate\Http\Response
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
            'address.fullPath' => ['required', 'string']
        ]);

        $data['active'] = true;

        if ($data['icon']) {
            $data['iconId'] = $fileService->saveBase64File($data['icon'])->id;
        }

        if ($data['cover']) {
            $data['coverId'] = $fileService->saveBase64File($data['cover'])->id;
        }

        $data['addressId'] = Address::create($data['address'])->id;
        $store = Store::create($data);

        return $store->toJson();
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
     * @return \Illuminate\Http\Response
     */
    public function edit(Store $store)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Store  $store
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Store $store)
    {
        //
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
