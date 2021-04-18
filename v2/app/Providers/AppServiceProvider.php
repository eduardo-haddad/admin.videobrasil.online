<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        if(app()->environment(['staging', 'prod'])){
            // Change the default storage directory for stating and prod environments.
            // The storage directory needs to be outside of the application directory to not
            // be overwritten when deploying.
            //
            // We also changed the file system disks root path located at /config/filesystems.php
            app()->useStoragePath(base_path('../../storage'));
        }

        // Custom Blade directives
        Blade::directive('date', function ($expression) {
            return "<?php echo ($expression)->format('d/m/Y'); ?>";
        });

        Blade::directive('time', function ($expression) {
            return "<?php echo ($expression)->format('H:i'); ?>";
        });

        Blade::directive('datetime', function ($expression) {
            return "<?php echo ($expression)->format('d/m/Y H:i'); ?>";
        });

        Blade::directive('words', function($string){
            return "<?php echo count(explode(' ', strip_tags($string))); ?>";
        });

        Blade::directive('number', function($expression) {
            return "<?php echo number_format($expression, 0, ',', '.'); ?>";
        });

        Blade::directive('currency', function($expression) {
            return "<?php echo 'R$ ' . number_format($expression, 2, ',', '.'); ?>";
        });

        Blade::directive('collapse', function($expression){
            return "<?php if(isset($expression) && $expression){ echo 'in'; } ?>";
        });

        Blade::if('admin', function(){
            return strpos(url('/'), env('FEEDBACK_DOMAIN')) === false ? true : false;
        });

        Blade::if('leadview', function(){
            return strpos(url('/'), env('FEEDBACK_DOMAIN')) === false ? false : true;
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // Using class based composers...
        View::composer('*', 'App\Http\ViewComposers\UserComposer');
        View::composer('*', 'App\Http\ViewComposers\GlobalComposer');
        View::composer('users.forms.roles', 'App\Http\ViewComposers\RoleComposer');
        View::composer('users.forms.notifications', 'App\Http\ViewComposers\NotificationComposer');
        View::composer('includes.clients', 'App\Http\ViewComposers\ClientComposer');
        View::composer('includes.agents', 'App\Http\ViewComposers\AgentComposer');

    }
}
