<?php
namespace Jeroenherczeg\Dog;

use Illuminate\Support\ServiceProvider;

class FollowServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $timestamp = date('Y_m_d_His', time());
        
        $this->publishes([
            __DIR__ . '/create_followers_table.php' => database_path('migrations/' . $timestamp . '_create_followers_table.php')
        ], 'migrations');
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
