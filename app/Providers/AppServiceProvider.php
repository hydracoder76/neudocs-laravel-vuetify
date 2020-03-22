<?php

namespace NeubusSrm\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\View\Factory;
use Laravel\Telescope\TelescopeServiceProvider;
use NeubusSrm\Http\Controllers\api\v1\Projects\ProjectsApiController;
use NeubusSrm\Lib\DataMappers\Formatter;
use NeubusSrm\Lib\DataMappers\FormatterImpl\LogFormatter;
use NeubusSrm\Lib\DataMappers\FormatterImpl\NeuDropdownFormatter;
use NeubusSrm\Lib\DataMappers\FormatterImpl\NeuProjectFormatter;
use NeubusSrm\Lib\DataMappers\FormatterImpl\RequestFormatter;
use NeubusSrm\Models\Auth\User;
use NeubusSrm\Services\LogService;
use NeubusSrm\Services\ProjectService;
use NeubusSrm\Services\RequestService;
use View, Blade, Auth, Gate;

/**
 * Class AppServiceProvider
 * @package NeubusSrm\Providers
 */
class AppServiceProvider extends ServiceProvider
{
	/**
	 * Bootstrap any application services.
	 *
	 * @return void
	 */
	public function boot() {
		View::share('pageTitle', 'Neubus SRM');
		View::composer('*', function ($view) {
			$username = '';
			$email = '';
			$companyName = '';
			$isAdmin = false;
            $defaultProjectId = '';
            $role = '';
			if (Auth::check()) {
				$user = Auth::user();
				$isAdmin = Auth::user()->role == User::LEVEL_ADMIN;

				$username = $user->name;
				$email = $user->email;
				$company = $user->company;
                $defaultProjectId = $user->default_project_id;
				$companyName = $company->company_name;
				$role = $user->role;
			}

			$view->with('isAdmin', $isAdmin);
			$view->with('username', $username);
			$view->with('email', $email);
			$view->with('companyName', $companyName);
            $view->with('defaultProjectId', $defaultProjectId);
            $view->with('userRole', $role);
			$menu = config('srm.menu');
			$authorizedMenu = $this->buildLeftNav($menu);

			if (count($authorizedMenu) > 0) {
				$menu = $authorizedMenu;
			}
            View::share('adminChildren', ['roots' => config('srm.menuRoots'), 'sub' => $menu]);
		});

		$this->customBlade();


	}

	/**
	 * Register any application services.
	 *
	 * @return void
	 */
	public function register() {
		$this->app->when(ProjectsApiController::class)->needs(Formatter::class)->give(NeuDropdownFormatter::class);
		$this->app->when(RequestService::class)->needs(Formatter::class)->give(RequestFormatter::class);
        $this->app->when(ProjectService::class)->needs(Formatter::class)->give(NeuProjectFormatter::class);
        $this->app->when(LogService::class)->needs(Formatter::class)->give(LogFormatter::class);

		// telescope
		if ($this->app->isLocal()) {
			$this->app->register(TelescopeServiceProvider::class);
		}
	}

	private function customBlade() {
		Blade::if('ituser', function() {
			return Auth::user()->role == User::ROLE_IT;
		});

		Blade::if('clientuser', function() {
			return Auth::user()->role == User::ROLE_CLIENT;
		});

		Blade::if('neubususer', function() {
			return Auth::user()->role == User::ROLE_NEUBUS;
		});

	}

	/**
	 * @param array $menu
	 * @return array
	 */
	private function buildLeftNav(array $menu) : array {
		// TODO: proof of concept
		if (Gate::allows('it.menu')) {
            $authorizedMenu['user'] = $menu['user'];
            $authorizedMenu['company'] = $menu['company'];
            $authorizedMenu['project'] = $menu['project'];
            $authorizedMenu['admin'] = $menu['admin'];
            $authorizedMenu['settings'] = $menu['settings'];
            $authorizedMenu['request']['request'] = $menu['request']['request'];
            $authorizedMenu['request']['todo'] = $menu['request']['todo'];
            $authorizedMenu['request']['dataentry'] = $menu['request']['dataentry'];
            $authorizedMenu['request']['review'] = $menu['request']['review'];
            $authorizedMenu['request']['completed'] = $menu['request']['completed'];
            $authorizedMenu['box'] = $menu['box'];
		}
		else if (Gate::allows('client.menu')) {
			$authorizedMenu['request']['request'] = $menu['request']['request'];
		}
		else if (Gate::allows('admin.menu')) {
			$authorizedMenu['request']['request'] = $menu['request']['request'];
			$authorizedMenu['user'] = $menu['user'];
            $authorizedMenu['admin']['log'] = $menu['admin']['log'];
		}
		else if (Gate::allows('neubus.menu')) {
            $authorizedMenu['request']['todo'] = $menu['request']['todo'];
		    $authorizedMenu['request']['dataentry'] = $menu['request']['dataentry'];
            $authorizedMenu['request']['review'] = $menu['request']['review'];
		    $authorizedMenu['request']['completed'] = $menu['request']['completed'];
            $authorizedMenu['box'] = $menu['box'];
        }
		else {
			return [];
		}
		return $authorizedMenu;
	}


}
