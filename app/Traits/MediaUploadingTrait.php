<?php

namespace App\Traits;

use App\Models\MediaFiles;
use App\Models\MediaImages;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use function Google\Auth\Cache\get;
use function Symfony\Component\String\length;


trait MediaUploadingTrait
{
    public $imageExtensions = ['jpg', 'jpeg', 'gif', 'png', 'bmp', 'webp', 'svg', 'svgz', 'cgm', 'djv', 'djvu', 'ico', 'ief', 'jpe', 'pbm', 'pgm', 'pnm', 'ppm', 'ras', 'rgb', 'tif', 'tiff', 'wbmp', 'xbm', 'xpm', 'xwd'];

    public function storeMedia(Request $request, $path = null)
    {
        $Original_path = empty($path) ? '/' : $path;
        // Validates file size
        if (request()->has('size')) {
            $this->validate(request(), [
                'file' => 'max:' . request()->input('size') * 1024,
            ]);
        }
        // If width or height is preset - we are validating it as an image
        if (request()->has('width') || request()->has('height')) {
//            $this->validate(request(), [
//                'file' => sprintf(
//                    'dimensions:max_width=%s,max_height=%s',
//                    request()->input('width', 100000),
//                    request()->input('height', 100000)
//                ),
//            ]);
        }
        if (empty($path)) {
            $path = public_path('storage/');
        } else {
            $path = public_path('storage/' . $path);
        }

        try {
            if (!file_exists($path)) {
                mkdir($path, 0755, true);
            }
        } catch (\Exception $e) {
        }

        $file = $request->file('file');

        $name = $file->getClientOriginalName();
        $mediaFile = new MediaFiles();
        $mediaFile->title = $file->getClientOriginalName();
        $mediaFile->alt = "";
        $mediaFile->extension = ($file->getClientMimeType() == 'application/pdf') ? "pdf" : $file->getExtension();
        $mediaFile->description = "";
        $mediaFile->height = 0;
        $mediaFile->width = 0;
        $mediaFile->size = $file->getSize();
        $mediaFile->type = $file->getClientMimeType();
        $mediaFile->path = $Original_path;
        $mediaFile->save();


        if ($file->move($path, $name)) {
            return response()->data([
                'meida_file' => $mediaFile,
                'title' => $file->getClientOriginalName(),
                'alt' => "",
                'extension' => $file->getExtension(),
                'description' => "",
                'height' => 0,
                'width' => 0,
                'size' => $mediaFile->size,
                'type' => $mediaFile->type,
                'path' => $Original_path,
                'original_name' => $file->getFilename(),
            ]);
        }
        return response()->error(trans('backend.global.error_message.image_not_uploaded'));


    }

    public function StoreImage($image_file, $width = 600, $height = 600, $path, $watermark = true)
    {

        $image = request()->file($image_file);
        $image_name = ($watermark == true ? 'watermark-' : '') . $image->getClientOriginalName();
//        dd($path[strlen($path)-1] == '/');

        if ($path == '/') {
            $path = '';
        }
        $path = str_replace('//', "/", $path);


        try {
            if (!file_exists(public_path('storage/' . $path))) {
                mkdir(public_path('storage/' . $path), 0755, true);
            }
        } catch (\Exception $e) {
        }
        $destinationPath = 'storage' . '/' . $path;
        if (file_exists(public_path($destinationPath . '/' . $image_name))) {
            $image_name = time().'-'.$image_name;
        }


        $img = \Intervention\Image\Facades\Image::make($image->getRealPath());
        $img->resize($width, $height);
        if ($watermark) {
//            $watermark_Setting = media_file(get_setting('watermark'));
            $media_File_watermark = MediaFiles::query()->where('id', get_setting('watermark'))->first();
            if (!empty($media_File_watermark)) {
                $watermark = \Intervention\Image\Facades\Image::make(public_path('storage' . $media_File_watermark->path . $media_File_watermark->title))->resize($width, $height);
                if (empty(get_setting('watermark_opacity')) && get_setting('watermark_opacity') != 0) {
                    $watermark->opacity(50);
                } else {
                    $watermark->opacity(get_setting('watermark_opacity'));
                }
                $img->insert($watermark, 'center');
            }

        }

        $img->save($destinationPath . '/' . $image_name);

        $mediaFile = new MediaFiles();
        $mediaFile->title = $image_name;
        $mediaFile->alt = "";
        $mediaFile->extension = $image->getClientOriginalExtension();
        $mediaFile->description = "";
        $mediaFile->height = $height;
        $mediaFile->width = $width;
        $mediaFile->size = $image->getSize();
        $mediaFile->type = $image->getType();
        $mediaFile->path = empty($path) ? '/' : $path;
        $mediaFile->save();

        return $mediaFile;
    }

    public function StoreAvatarImage($image_file, $id, $guard, $width = 50, $height = 50)
    {
        $image = request()->file($image_file);
        $image_name = md5($id);

        Storage::disk('public')->makeDirectory($guard);
        $img = \Intervention\Image\Facades\Image::make($image->getRealPath());
        $img->resize($width, $height, function ($constraint) {
            $constraint->aspectRatio();
        })->save(Storage::disk("public")->path($guard) . '/' . $image_name . '.' . $image->extension());
        return response()->data([
            'title' => $img->filename . '.' . $image->extension(),
            'alt' => "",
            'extension' => $image->extension(),
            'description' => "",
            'height' => $height,
            'width' => $width,
            'size' => $image->getSize(),
            'type' => $image->getType(),
            'path' => 'storage/' . $guard . '/',
            'original_name' => $image->getClientOriginalName(),
        ]);
    }


}
