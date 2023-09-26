<?php

namespace App\Services;

use App\Models\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FileService
{
    /**
     * @param string $base64
     * @return File
     */
    public function saveBase64File(string $base64)
    {
        $fileData = explode(',', $base64);
        $fileType = [];
        preg_match('/:(.*?);/', $fileData[0], $fileType);
        $file = base64_decode($fileData[1]);
        $fileExt = explode('/', $fileType[1])[1];
        $fileName = 'storage/' . date('Y/m/') . $fileType[1] . '/' . Str::uuid() . '.' . $fileExt;
        Storage::disk('public')->put($fileName, $file);
        return File::create([
            'path' => $fileName,
            'type' => $fileType[1],
            'extension' => $fileExt
        ]);
    }
}
