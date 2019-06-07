<?php

namespace App\Http\Middleware;

use App\Module;
use App\UserGroup;
use Auth;
use Closure;

class HasAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $module)
    {
        $user_group = Auth::user()->user_group;
        $modules = Module::all();

        $getModuleAccess = UserGroup::where('id', $user_group)->value('modules_access');
        $modules_access = explode(',', $getModuleAccess);

        if (!in_array($module, $modules_access)) {
            return redirect('/');
        }

        return $next($request);
    }
}
