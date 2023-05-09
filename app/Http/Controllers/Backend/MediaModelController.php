<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\MediaFiles;
use App\Traits\MediaUploadingTrait;
use Google\Service\Storage;
use Illuminate\Http\Request;
use function Symfony\Component\String\length;

class MediaModelController extends Controller
{
    use MediaUploadingTrait;
    public $pagination = 24;

    function get(Request $request)
    {
        $path = '/';
        if ($request->has('storage_path') && !empty($request->storage_path) && empty($request->search)) {
            $path = $request->storage_path;
        }
        $folders = [];
        if (empty($request->search)) {
            $folders_data = \Storage::disk('public')->directories($path);
        } else {
            $folders_data = \Storage::disk('public')->directories($path, true);
        }
        $page = empty($request->page) ? 1 : $request->page;
        sort($folders_data);
        foreach ($folders_data as $key => $item) {
            $item_folder = explode('/', $item);
            if (!empty($request->search) && preg_match('/' . $request->search . '/i', $item_folder[count($item_folder) - 1])) {
//                $folders[] = $item_folder[count($item_folder) - 1];
                if (isset($folders[$item_folder[count($item_folder) - 1]])) {
                    $key = (isset($item_folder[0]) ? $item_folder[0] . '>' : '') . $item_folder[count($item_folder) - 1];
                    $folders[$key] = $item;
                } else {
                    $folders[$item_folder[count($item_folder) - 1]] = $item;
                }
            } else if (empty($request->search)) {
//                $folders[] = $item_folder[count($item_folder) - 1];
                if (isset($folders[$item_folder[count($item_folder) - 1]])) {
                    $key = (isset($item_folder[0]) ? $item_folder[0] . '>' : '') . $item_folder[count($item_folder) - 1];
                    $folders[$key] = $item;
                } else {
                    $folders[$item_folder[count($item_folder) - 1]] = $item;
                }
            }
        }
        $pagination = 1;
        if ($request->page >= 1 && !empty($request->page)) {
            $pagination = $request->page;
        }

        $skip = ($pagination - 1) * $this->pagination;
        $files = MediaFiles::query()->orderByDesc('id');
        if ($request->has('search') && !empty($request->search)) {
            $files = $files->where('title', 'like', '%' . $request->search . '%');
        } else {
            $files = $files->where('path', $path);
        }
        if ($request->has('type') && $request->type != 'undefined' && $request->type != 'null' && !empty($request->type)) {
            $type = $request->type;
            if ($type == "image") {

                $files = $files->whereIn('extension', $this->imageExtensions);
            } else {
                $files = $files->where('extension', $type);
            }

        }
        $count_folder = count($folders);
        $count_files = MediaFiles::query();
        if ($request->has('search') && !empty($request->search)) {
            $count_files = $count_files->where('title', 'like', '%' . $request->search . '%');
        } else {
            $count_files = $count_files->where('path', $path);
        }
        $get_files_bool = true;
        if ($path != '/') {
            $this->pagination -= 1;
        }
        if ($count_folder <= $this->pagination && $page == 1) {
            $files = $files->limit($this->pagination - $count_folder);
            $files = $files->skip($skip);

        } else if ($count_folder <= $this->pagination && $page != 1) {
            $files = $files->limit($this->pagination);
            $files = $files->skip(($skip - $count_folder));
            $skip -= $count_folder;
            $folders = [];
        } else if ($count_folder >= $this->pagination) {

            $new_folder = [];
            $ic = 0;
            foreach ($folders as $key => $folder) {
                if ($ic >= $this->pagination * ($page - 1) && $ic < $this->pagination * ($page)) {
                    $new_folder[$key] = $folder;
                }
                $ic++;
            }
            $files = $files->limit($this->pagination - count($new_folder));

            if ($skip - $count_folder > 0) {
                $files = $files->skip(($skip - $count_folder));
            }
            $folders = $new_folder;
        } else {
            $files = $files->limit($this->pagination);
            $files = $files->skip($skip);
        }
        if ($get_files_bool) {
            $files = $files->get();
            foreach ($files as $item) {
                $item->display_title = \Str::limit($item->title, 10);
            }
        }
        if ($request->has('type') && $request->type != 'undefined' && $request->type != 'null' && !empty($request->type)) {
            $type = $request->type;
            if ($type == "image") {

                $count_files = $count_files->whereIn('extension', $this->imageExtensions);
            } else {
                $count_files = $count_files->where('extension', $type);
            }

        }
//        $count_files = MediaFiles::query()->where('path', $path);
        if (!empty($request->search)) {
            $count_files = $count_files->where('title', 'like', '%' . $request->search . '%');
        }

        $count_files = $count_files->count();
        $count_files_at_page = $this->pagination;
        $data_selected = [];

        if (!empty($request->data_input)) {
            if (is_array(json_decode($request->data_input))) {
                $data_selected = MediaFiles::query()->whereIn('id', json_decode($request->data_input))->get();
            } else {
                $data_selected = MediaFiles::query()->where('id', $request->data_input)->get();
            }
        }
        return response()->data([
            'path' => $path,
            'pagination' => $pagination,
            'data_selected' => $data_selected,
            'skip' => $skip,
            'count_files_at_page' => $count_files_at_page,
            'files' => $files,
            'folders' => $folders,
            'count_files' => $count_files + $count_folder
        ]);
    }

}
