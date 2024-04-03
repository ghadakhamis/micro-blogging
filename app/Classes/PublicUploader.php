<?php 

namespace App\Classes;

use App\Interfaces\UploaderInterFace;
use Carbon\Carbon;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class PublicUploader implements UploaderInterFace
{
    public function upload(UploadedFile $file, string $directory): string
    {
        $this->createDirectory($directory);
        $fileName = $this->generateFileName($file);

        /** @var FilesystemAdapter $disk */
        $disk     = Storage::disk('public');
        
        $path = $disk->putFileAs($directory, $file, $fileName);
        return $disk->url($path);
    }

    private function createDirectory(string $directory): void
    {
        $path = storage_path('app/public/' . $directory.'/');
        if(!is_dir($path)) {
            Storage::makeDirectory('/'.$directory.'/');
        }
    }

    private function generateFileName(UploadedFile $file): string
    {
        return Carbon::now()->timestamp. '.' . $file->getClientOriginalExtension();
    }
}
