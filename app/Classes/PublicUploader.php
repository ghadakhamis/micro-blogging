<?php 

namespace App\Classes;

use App\Interfaces\UploaderInterFace;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;
use Carbon\Carbon;

class PublicUploader implements UploaderInterFace
{
    public function upload(UploadedFile $file, string $directory)
    {
        $this->createDirectory($directory);
        $fileName = $this->generateFileName($file);
        
        $path = Storage::disk('public')->putFileAs($directory, $file, $fileName);
        return Storage::disk('public')->url($path);
    }

    private function createDirectory($directory)
    {
        $path = storage_path('app/public/' . $directory.'/');
        if(!is_dir($path)) {
            Storage::makeDirectory('/'.$directory.'/');
        }
    }

    private function generateFileName(UploadedFile $file)
    {
        return Carbon::now()->timestamp. '.' . $file->getClientOriginalExtension();
    }
}
