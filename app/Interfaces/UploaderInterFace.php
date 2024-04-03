<?php 

namespace App\Interfaces;

use Illuminate\Http\UploadedFile;

interface UploaderInterFace
{
    /**
     * @param  UploadedFile $file
     * @param  string $directory
     * @return mixed
     */
    public function upload(UploadedFile $file, string $directory);
}
