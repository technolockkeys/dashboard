<?php

namespace App\Providers;

use Illuminate\Routing\ResponseFactory;
use Illuminate\Support\ServiceProvider;
use Symfony\Component\ErrorHandler\Debug;

class ResponseServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot(ResponseFactory $factory)
    {
        $factory->macro('data', function ($data = false,$code = 200,$isPaginate = false) use ($factory) {
                if($isPaginate){
                    $data = [
                        'items'=>$data,
                        'current_page'=> $data->currentPage(),
                        'last_page'=> $data->lastPage(),
                    ];
                }
                $format =[
                    'data'=> $data,
                ];
                return $factory->make($format,$code);

        });

         $factory->macro('error' , function ($message ,$data = false) use ($factory){
            $executionEndTime = microtime(true);
            $seconds = $executionEndTime - LARAVEL_START;
            $seconds = number_format($seconds, 3) . ' seconds';

            $format = [
                'message' => $message,
                'execution' => $seconds
            ];
            $format['data'] = $data ?: [];
            return $factory->make($format,400);
        });


         //api responses
        $factory->macro('api_data', function ($data = false,$code = 200,$isPaginate = false) use ($factory) {
            if($isPaginate){
                $data = [
                    'items'=>$data,
                    'current_page'=> $data->currentPage(),
                    'last_page'=> $data->lastPage(),
                ];
            }
            $format = $data;
            return $factory->make($format,$code);

        });

        $factory->macro('api_error' , function ($message ,$code = 400, $data = false) use ($factory){
            $format = [
                'message' => $message,
            ];
            $format['data'] = $data ?: [];

            return $factory->make($format,$code);
        });

        $factory->macro('api_warning' , function ($key, $value,) use ($factory){

            $format = [
                $key => $value,
            ];
            return $factory->make($format,400);
        });

    }
}
