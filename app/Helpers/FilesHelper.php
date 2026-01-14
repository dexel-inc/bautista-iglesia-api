<?php

namespace App\Helpers;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class FilesHelper
{
    public static function save(string $path, UploadedFile $file): string
    {
        $disk = config('filesystems.default');
        $fileName = $file->hashName();

        $filePath = Storage::disk($disk)->putFileAs($path, $file, $fileName);

        return $filePath;
    }
}
