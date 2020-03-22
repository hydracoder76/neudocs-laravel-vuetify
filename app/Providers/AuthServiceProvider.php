<?php

namespace NeubusSrm\Providers;

use Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

/**
 * Class AuthServiceProvider
 * @package NeubusSrm\Providers
 */
class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'NeubusSrm\Model' => 'NeubusSrm\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot() {
        $this->registerPolicies();

	    $this->customGates();

    }


	private function customGates() {
		Gate::define('it.menu', 'NeubusSrm\Policies\ItPolicy@menu');
		Gate::define('neubus.menu', 'NeubusSrm\Policies\NeubusPolicy@menu');
		Gate::define('client.menu', 'NeubusSrm\Policies\ClientPolicy@menu');
		Gate::define('admin.menu', 'NeubusSrm\Policies\AdminPolicy@menu');
	}
}
