<?php

namespace App\Http\Middleware;

use App\MembershipType;
use Auth;
use Closure;

class IsActive
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (Auth::guest()) {
            return redirect('login');
        }

        if (Auth::user()->status == 'inactive') {
            return redirect('login')->with(Auth::logout())->with('error', 'You are not allowed to access the website.');
        }

        if (!$request->session()->has('portal')) {
            $portal = Auth::user()->portal_type;
            session(['portal' => $portal]);

            $portalLabel = MembershipType::where('id', $portal)->value('type');
            session(['portalLabel' => $portalLabel]);
        }

        return $next($request);

    }
}
