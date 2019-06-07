<?php

namespace App\Providers;

use App\MembershipType;
use App\User;
use App\UserGroup;
use Auth;
use Illuminate\Support\ServiceProvider;

class ViewComposerServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        view()->composer('includes.nav', function ($view) {
            $user_group = Auth::user()->user_group;

            $getModuleAccess = UserGroup::where('id', $user_group)->value('modules_access');
            $modules_access = explode(',', $getModuleAccess);

            $canSwitch = User::where('id', Auth::user()->id)->value('can_change_portal');

            $portal = session('portal');
            if ($canSwitch == 0) {
                $portal_type = Auth::user()->portal_type;
                session(['portal' => $portal_type]);
                session(['portalLabel' => MembershipType::where('id', $portal_type)->value('type')]);
            }

            $view->with('modules_access', $modules_access);
            $view->with('types', MembershipType::all());
            $view->with('can_switch', $canSwitch);
        });
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
