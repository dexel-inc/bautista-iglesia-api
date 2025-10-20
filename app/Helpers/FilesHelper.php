<?php

namespace App\Helpers;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class FilesHelper
{
    public static function save(string $path, UploadedFile $file): string
    {
        return Storage::disk(config('filesystems.default'))->putFileAs($path, $file, $file->hashName());
    }

    public static function get(string $path): ?string
    {
        return Storage::disk(config('filesystems.default'))->has($path)
            ? Storage::disk(config('filesystems.default'))->url($path)
            : null;
    }

    public static function delete(string $path): bool
    {
        return Storage::delete($path);
    }
}
