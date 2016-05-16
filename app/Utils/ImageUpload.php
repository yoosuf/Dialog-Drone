<?php

namespace App\Utils;

class ImageUpload
{



    public function process($file, $destination)
    {
        try {
            $filename = time() . str_random(16) . '.' . strtolower($file->getClientOriginalExtension());
            $file->move(base_path() . '/public/uploads/' . $destination, $filename);
            $uploadPath = '/uploads/' . $destination . '/' . $filename;
            return $uploadPath;
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
}