<?php

namespace App\Http\Controllers;

use App\AuditLog;
use App\Module;
use App\UserGroup;
use Auth;
use DB;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */

    public function index()
    {
        //Green Card or Platinum
        $portal = session('portal');

        if (!$portal || $portal == '') {
            session(['portal' => 1]);
            session(['portalLabel' => 'Green Card']);
        }

        //Update last login
        DB::table('users')
            ->where('id', Auth::user()->id)
            ->update(['last_login' => date('Y-m-d H:i:s')]);

        //Create entry in audit log
        AuditLog::insert([
            'description' => 'Successfully Login',
            'user_id' => Auth::user()->id,
            'subject_type' => getenv('USERDOMAIN'),
            'module_id' => 7,
            'created_at' => date('Y-m-d H:i:s'),
        ]);

        //Identify user role
        $user_group = Auth::user()->user_group;

        switch ($user_group) {
            case 1:
                $redirectTo = '/dashboard';
                break;
            case 2:
                $redirectTo = '/dashboard';
                break;
            case 3:
                $redirectTo = '/redemptions';
                break;
            default:
                $getModuleAccess = UserGroup::where('id', $user_group)->value('modules_access');
                $modules_access = explode(',', $getModuleAccess);

                $first = $modules_access[0];
                $redirectTo = Module::where('id', $first)->value('url');
        }

        return redirect($redirectTo);
    }

    public function changePortal(Request $request)
    {
        $label = DB::table('membership_types')->select('type')->where('id', $request->type)->value('type');

        session(['portal' => $request->type]);
        session(['portalLabel' => $label]);

        return redirect('/');
    }
}
