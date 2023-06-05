<?php

namespace App\Common;

use Illuminate\Support\Facades\Storage;
use App\Models\Traits\Image as ImageTrait;
use Carbon\Carbon;
use App\Common\Timezone;
use Intervention\Image\Facades\Image as ImageResize;

class Image
{
    const CACHE_PATH = 'cache/';
    const IMAGES_PATH = 'images/';
    use ImageTrait;
    /**
     * Upload image to server with file
     *
     * @param Object $file
     * @param Array $options
     * @param String $name
     * @param String $prefix
     * @return string
     */
    public static function generateStorageImage($file, $options = ['visibility' => 'public'], $name = null, $path = '')
    {
        if (!$path) {
            $path = self::IMAGES_PATH . 'dates/'  . Carbon::now()->tz(Timezone::TIMEZONE_DEFAULT)->format('Y-m-d') . '/' . time() . uniqid();
        }

        $filename = $file->getClientOriginalName();
        if (!empty($name)) {
            $extension = $file->clientExtension();
            if (empty($extension)) {
                $extension = $file->getClientOriginalExtension();
                if (empty($extension)) {
                    $parse = explode('.', $file->getClientOriginalName());
                    $extension = end($parse);
                }
            }
            $filename = $name . '.' . $extension;
        }

        Storage::putFileAs(
            $path,
            $file,
            $filename,
            $options
        );

        return $path . '/' . $filename;
    }

    /**
     * delete image on server
     *
     * @param String $prefix
     * @return boolean
     */
    public static function deleteStorageImage($path = null)
    {
        if (!$path || !Storage::exists($path)) {
            return true;
        }
        return Storage::delete($path);
    }

    /**
     * move image from server with path
     *
     * @param String $source
     * @param String $destination
     * @return boolean
     */
    public static function moveImage($source, $destination)
    {
        return Storage::move($source, $destination);
    }

    public static function createCacheImageIfNotExist($imagePath)
    {
        $cacheImage = self::CACHE_PATH . $imagePath;
        if (Storage::exists($cacheImage)) {
            return $cacheImage;
        }

        $img = ImageResize::make(Storage::get($imagePath));
        $img->resize(100, 100, function ($constraint) {
            $constraint->aspectRatio();
        });
        Storage::put($cacheImage, $img->stream());

        return $cacheImage;
    }
}
