<?php
namespace App\Http\Helpers;

class UploadHelper
{

    public static function upload_image($folder, $file)
    {
        if (!file_exists($folder . '/' . $file->hashName())) {
            $name = $file->hashName();
            $file->move($folder.'/', $name);
        }
        return $name;
    }
}