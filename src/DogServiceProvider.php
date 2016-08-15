<?php
namespace Jeroenherczeg\Dog;

use Illuminate\Support\ServiceProvider;

class DogServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/create_followers_table.php' => database_path('migrations/2016_08_15_000000_create_followers_table.php')
        ], 'test');
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
