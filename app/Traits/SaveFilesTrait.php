<?php

namespace App\Traits;


trait SaveFilesTrait{

    public function save_files($files)
    {
        $response = [];
        try {
            if (!file_exists(public_path('storage/tickets'))) {
                mkdir(public_path('storage/tickets'), 0755, true);
            }
        } catch (\Exception $e) {
        }

        foreach ($files as $file) {
            $name = $file->getClientOriginalName();
            $hash_name = $file->hashName();
            $file->move(public_path('storage/tickets'), $hash_name);

            $obj = [];
            $obj['image_data'] = $name;
            $obj['hashed_name'] = $hash_name;
            $obj['path'] = 'storage/tickets';
            $response[] = $obj;
        }
        $this->files = json_encode($response);
        $this->save();

        return $response;

    }
}