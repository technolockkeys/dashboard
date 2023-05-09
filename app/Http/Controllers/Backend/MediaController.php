<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\Media\CheckRequest;
use App\Http\Requests\Backend\Media\CreateFolderRequest;
use App\Http\Requests\Backend\Media\DeleteFolderRequest;
use App\Http\Requests\Backend\Media\DeleteRequest;
use App\Http\Requests\Backend\Media\DetaileRequest;
use App\Http\Requests\Backend\Media\EditRequest;
use App\Http\Requests\Backend\Media\GetFolderRequest;
use App\Http\Requests\Backend\Media\SetFolderRequest;
use App\Http\Requests\Backend\Media\UploadFileRequest;
use App\Models\MediaFiles;
use App\Traits\MediaUploadingTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use function Symfony\Component\Finder\path;

class MediaController extends Controller
{
    use MediaUploadingTrait;

    public $pagination = 30;

    function index(Request $request)
    {
        if (!permission_can('show media', 'admin')) {
            return abort(403);
        }
        $path = '/';
        if (!$request->has('storage_path') && !empty($request->storage_path)) {
            $path = $request->storage_path;
        }
        $files = MediaFiles::query()->where('path', $path)->limit(30)->orderByDesc('id')->get();
        $count_files = MediaFiles::query()->where('path', $path)->count();
        $pagination = $this->pagination;
        return view('backend.media.index', compact('files', 'pagination', 'count_files'));
    }

    function upload_files(UploadFileRequest $request)
    {
        $path = \request('storage_path');
        $width = $request->has('width') ? $request->width : 600;
        $height = $request->has('height') ? $request->height : 600;
        $extension = $request->file('file')->extension();
        if (in_array($extension, $this->imageExtensions)) {
            $watermark = ($request->watermark == 'true' && get_setting('watermark_status') == 1);
            $original_size = $this->StoreImage('file', $width, $height, $path, $watermark);
            $medium_size = $this->StoreImage('file', intval($width / 2), intval($height / 2), '/medium/' . ($path == '/' ? "" : $path), $watermark);
            $thumbnail = $this->StoreImage('file', intval($width / 3), intval($height / 3), '/thumbnail/' . ($path == '/' ? "" : $path), $watermark);
            $ids = json_encode(['original_id' => $original_size->id, 'medium_id' => $medium_size->id, 'thumbnail_id' => $thumbnail->id]);
            $original_size->related_images_ids = $ids;
            $medium_size->related_images_ids = $ids;
            $thumbnail->related_images_ids = $ids;
            $original_size->save();
            $medium_size->save();
            $thumbnail->save();
            if ($watermark) {
                $original_size_without_watermark = $this->StoreImage('file', $width, $height, $path, false);
                $medium_size_without_watermark = $this->StoreImage('file', 300, 300, '/medium/' . ($path == '/' ? "" : $path), false);
                $thumbnail_without_watermark = $this->StoreImage('file', 200, 200, '/thumbnail/' . ($path == '/' ? "" : $path), false);
                $ids = json_encode(['original_id' => $original_size->id, 'medium_id' => $medium_size->id, 'thumbnail_id' => $thumbnail->id]);
                $original_size_without_watermark->related_images_ids = $ids;
                $medium_size_without_watermark->related_images_ids = $ids;
                $thumbnail_without_watermark->related_images_ids = $ids;
                $original_size_without_watermark->save();
                $medium_size_without_watermark->save();
                $thumbnail_without_watermark->save();
            }

        } else {
            return $this->storeMedia($request, $path);
        }
    }

    function get_media_files(Request $request)
    {

        $pagination = 1;
        if ($request->page >= 1) {
            $pagination = $request->page;
        }
        $path = '/';
        if ($request->has('storage_path') && !empty($request->storage_path)) {
            $path = $request->storage_path;
        }
        $folders_data = Storage::disk('public')->directories($path, false);
        $folders = [];
        for ($i = 0; $i < count($folders_data); $i++) {
            $str = explode($path, '/' . $folders_data[$i]);

            if (isset($str[1])) {
                if (!empty($request->search) && preg_match($request->search, $str[1])) {
                    $folders[] = $str[1];
                } else if (empty($request->search)) {
                    $folders[] = $str[1];
                }
            } else {
                $folders[] = '/';
            }
        }
        $skip = ($pagination - 1) * $this->pagination;
        if (count($folders) > $this->pagination) {
            $files = MediaFiles::query()->where('path', $path)->skip($skip)->limit(1)->orderByDesc('id');

        } else {
            $files = MediaFiles::query()->where('path', $path)->skip($skip)->limit($this->pagination - count($folders))->orderByDesc('id');

        }
        if ($request->has('search') && !empty($request->search)) {
            $files = $files->where('title', 'like', '%' . $request->search . '%');
        }
        $files = $files->get();
        foreach ($files as $item) {

            $item->display_title = \Str::limit($item->title, 10);

        }
        $count_files = MediaFiles::query()->where('path', $path);
        $count_files = $count_files->where('title', 'like', '%' . $request->search . '%');

        $count_files = $count_files->count();
        $count_files_at_page = $this->pagination;
        return response()->data([
            'path' => $path,
            'pagination' => $pagination,
            'skip' => $skip,
            'count_files_at_page' => $count_files_at_page - count($folders),
            'files' => $files,
            'folders' => $folders,
            'count_files' => $count_files + count($folders)
        ]);
    }

    function delete_files(DeleteRequest $request)
    {

        $ids = $request->ids;
        foreach ($ids as $item) {
            $media = MediaFiles::find($item);
            \Storage::disk('public')->delete($media->path . $media->title);
            $media->delete();
        }
        return response()->data(['message' => trans('backend.global.success_message.deleted_successfully')]);

    }

    function file_details(DetaileRequest $request)
    {

        $mediaFile = MediaFiles::find($request->id);
        $view = view('backend.media.ajax_details', compact('mediaFile'))->render();
        return response()->data(['view' => $view]);
    }

    function update(EditRequest $editRequest)
    {

        $mediaFile = MediaFiles::find($editRequest->id);

        \Storage::disk('public')->move($mediaFile->path . '/' . $mediaFile->title, $mediaFile->path . '/' . $editRequest->title . '.' . $mediaFile->extension);


        $mediaFile->title = $editRequest->title . '.' . $mediaFile->extension;
        $description = [];
        $open_graph = [];
        $scale = [];
        $alt = [];
        $rel = [];
        foreach (get_languages() as $lang) {
            $description[$lang->code] = $editRequest->get('description_' . $lang->code);
            $open_graph [$lang->code] = $editRequest->get('open_graph_' . $lang->code);
            $scale      [$lang->code] = $editRequest->get('scale_' . $lang->code);
            $alt        [$lang->code] = $editRequest->get('alt_' . $lang->code);
            $rel        [$lang->code] = $editRequest->get('rel_' . $lang->code);
        }
        $mediaFile->description = $description;
        $mediaFile->open_graph = $open_graph;
        $mediaFile->scale = $scale;
        $mediaFile->alt = $alt;
        $mediaFile->rel = $rel;
        $mediaFile->save();

        Cache::forget('media_'.$mediaFile->id);
        Cache::forget('get_multisized_image_'.$mediaFile->id);
        return response()->data(['message' => trans('backend.global.success_message.updated_successfully'), 'mediaFile' => $mediaFile]);
    }

    function check(CheckRequest $checkRequest)
    {

        $file = MediaFiles::query()->where('id', $checkRequest->id)->first();
        $title = $checkRequest->title;
        $check = MediaFiles::query()->whereNot('id', $checkRequest->id)
            ->where('title', $title . '.' . $file->extension)
            ->where('path', $file->path)
            ->count();
        return response()->data(['check' => !$check]);
    }

    function create_folder(CreateFolderRequest $request)
    {
        $folder_name = str_replace(' ', '-', $request->folder_name); // Replaces all spaces with hyphens.
        $folder_name = preg_replace('/[^A-Za-z0-9\-]/', '-', $folder_name); // Removes special chars.


        if (!Storage::disk('public')->directoryExists($request->path . '/' . $folder_name)) {
            Storage::disk('public')->makeDirectory($request->path . '/' . $folder_name);
            return response()->data([
                'new_folder_name' => $folder_name,
                'path' => $request->path
            ]);
        } else {
            return response()->error(trans('backend.media.already_exists_please_select_other_name'));
        }
    }

    function delete_folder(DeleteFolderRequest $request)
    {
        $path = $request->path;
        $folder = $request->folder_name;

        Storage::disk('public')->deleteDirectory($path);
        $path = "/{$path}/";
        $medias = MediaFiles::query()->where('path', $path)->get();
        foreach ($medias as $item) {
            \Storage::disk('public')->delete($item->path . $item->title);
            $item->delete();
        }
        return response()->data([]);
    }

    #region cut
    function cut_get_folder(GetFolderRequest $request)
    {
        $path = $request->path;
        $file_name = $request->file_name;
        $folders = [];
        if ($file_name != "../") {
            //when click on folder
            $new_path = $path . $file_name . '/';
        } elseif ($path == '/' && $file_name == '/') {
            $new_path = '/';
        } else {
            //when click back
            $old_path_array = explode('/', $path);
            $i = 0;
            $new_path = [];
            foreach ($old_path_array as $key => $item) {
                if (++$i != $key) {
                    $new_path[] = $item;
                }
            }
            if (!empty($new_path)) {
                $new_path = implode('/', $new_path) . '/';
            } else {
                $new_path = '/';
            }
        }

        if ($new_path != '/' && $new_path != '//' && $new_path != '///') {
            $folders[] = '../';
        }
        if ($new_path == '/' || $new_path == '//' || $new_path == '///') {
            $new_path = '/';
        }

        $folders_data = Storage::disk('public')->directories($new_path, false);

        for ($i = 0; $i < count($folders_data); $i++) {
            $str = explode($new_path, '/' . $folders_data[$i]);
            if (isset($str[1]) && $str[1] != "") {
                $folders [] = str_replace('/', '', $str[1]);
            }
        }
        $files = MediaFiles::query()->where('path', $new_path)->select('title', 'type')->get();
        $default_value = media_file(get_setting('default_images'));

        return response()->data(['folders' => $folders, 'path' => $new_path, 'files' => $files, 'default_image' => $default_value]);
    }

    function cut_set_folder(SetFolderRequest $request)
    {
        $media = MediaFiles::find($request->file_id);
        Storage::disk('public')->move($media->path . $media->title, $request->path . $media->title);
        $media->path = $request->path;
        $media->save();
        return response()->data(['media' => $media]);
    }
#endregion

}
