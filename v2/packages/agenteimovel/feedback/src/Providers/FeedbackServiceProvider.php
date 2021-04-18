<?php

namespace Feedback\Providers;

use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider;

class FeedbackServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot(Router $router)
    {
        $router->aliasMiddleware('questionnaire', \Feedback\Middleware\Questionnaire::class);
        $router->aliasMiddleware('UserAccess', \Feedback\Middleware\UserAccess::class);
        $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');
        $this->loadViewsFrom(__DIR__ . '/../views', 'feedback');
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
