<?php

namespace ErpNET\Profiting\Calendar\Providers;

use Illuminate\Support\ServiceProvider;

class ErpnetProfitingCalendarServiceProvider extends ServiceProvider
{
    protected $commands = [
        \ErpNET\Profiting\Calendar\Console\Commands\Install::class,
    ];    
    
    protected $projectRootDir;

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->projectRootDir = __DIR__.DIRECTORY_SEPARATOR."..".DIRECTORY_SEPARATOR."..".DIRECTORY_SEPARATOR;
        $routesDir = $this->projectRootDir."routes".DIRECTORY_SEPARATOR;

        //Routing
        include $routesDir."web.php";
        
        $this->publishMigrations();
        
        $this->listenEvents();
        
        $this->registerTranslations();
        
        $this->registerViews();

    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        // register the artisan commands
        $this->commands($this->commands);
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [];
    }
    
    private function publishMigrations()
    {
        $path = $this->getMigrationsPath();
        $this->publishes([$path => database_path('migrations')], 'migrations');
    }
    
    private function getMigrationsPath()
    {
        return $this->projectRootDir . 'database/migrations/';
    }
    
    private function listenEvents()
    {
        //$this->app['events']->listen(\App\Events\AdminMenuCreated::class, \ErpNET\Profiting\Milk\Listeners\AdminMenu::class);
    }
    
    /**
     * Register translations.
     *
     * @return void
     */
    private function registerTranslations()
    {
        $langPath = $this->projectRootDir . 'resources/lang';
        
        if (is_dir($langPath)) {
            $this->loadTranslationsFrom($langPath, 'erpnet-profiting-calendar');
        }
    }
    
    
    /**
     * Register views.
     *
     * @return void
     */
    private function registerViews()
    {
        $viewPath = resource_path('views/vendor/erpnet-profiting-calendar');
        
        $sourcePath = $this->projectRootDir . 'resources/views';
        
        $this->publishes([
            $sourcePath => $viewPath
        ]);
        
        $this->loadViewsFrom(array_merge(array_map(function ($path) {
            return $path . '/vendor/erpnet-profiting-calendar';
        }, \Config::get('view.paths')), [$sourcePath]), 'erpnet-profiting-calendar');
    }
    
    
}
