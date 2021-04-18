<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\AVM\AVM' => 'App\Policies\AVMPolicy',
        'App\Campaign' => 'App\Policies\CampaignPolicy',
        'App\Lead\Lead' => 'App\Policies\Lead\LeadPolicy',
        'App\Lead\Pre' => 'App\Policies\Lead\PrePolicy',
        'App\User' => 'App\Policies\UserPolicy',
        'App\Snippet' => 'App\Policies\WebsitePolicy',
        'App\XmlIntegration\IntegrationClient' => 'App\Policies\XmlIntegrationPolicy'
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        //
    }
}
