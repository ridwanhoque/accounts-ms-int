<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class HelperServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        
        require_once $file = app_path().'/Http/Helpers/MessageHelper.php';
        // dd($file);
        if(\file_exists($file)){
            // require_once $file;
        }

        // foreach(glob(app_path(). 'Helpers/*.php') as $file){
        //     if(\file_exists($file)){
        //         require_once($file);
        //     }
        // }
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
