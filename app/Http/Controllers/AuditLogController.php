<?php

namespace App\Http\Controllers;

use App\AuditLog;
use App\Module;
use Auth;
use DB;
use Illuminate\Http\Request;

class AuditLogController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $query = AuditLog::query();

        $search = $request->input('search');
        $module = $request->input('module');

        if ($request->has('per_page')) {
            $per_page = $request->input('per_page');
        } else {
            $per_page = 10;
        }

        if ($request->has('sort')) {
            $sort = $request->input('sort');
            $dir = $request->input('dir');
        } else {
            $sort = 'created_at';
            $dir = 'desc';
        }

        if ($module != null || $module != '') {
            $query = $query->where('module_id', $module);
        }

        // $logs = DB::table('audit_logs')
        //     ->select('audit_logs.*', 'users.name AS u_name', 'modules.module_name AS m_name')
        //     ->join('users', 'users.id', '=', 'audit_logs.user_id')
        //     ->join('modules', 'modules.id', '=', 'audit_logs.module_id')
        //     ->where('users.name', 'like', '%' . $search . '%')
        //     ->orWhere('audit_logs.description', 'like', '%' . $search . '%')
        //     ->orderBy($sort, $dir)
        //     ->paginate($per_page);

        // $logs = AuditLog::with(['module',
        //     'user' => function ($query) use ($search) {
        //         //$query->where('name', 'like', '%' . $search . '%');
        //     }])
        //     ->where('description', 'like', '%' . $search . '%')
        //     ->orderBy($sort, $dir)
        //     ->paginate($per_page);

        $query = $query->where('description', 'like', '%' . $search . '%');
        $query = $query->orderBy($sort, $dir);
        $logs = $query->paginate($per_page);

        $modules = Module::all();
        return view('audit_log.index', compact('logs', 'modules', 'module', 'search', 'per_page'));
    }

    public function show($id)
    {
        $log = AuditLog::with(['module', 'user'])->find($id);

        $module = DB::table('modules')->where('id', '=', $log->module_id)->first();
        $user = DB::table('users')->where('id', '=', $log->user_id)->first();

        return view('audit_log.show', compact('log', 'module', 'user'));
    }

    public function store($description, $module, $props)
    {
        AuditLog::insert([
            'description' => $description,
            'user_id' => Auth::user()->id,
            'subject_type' => getenv('USERDOMAIN'),
            'module_id' => $module,
            'properties' => json_encode($props),
            'created_at' => date('Y-m-d H:i:s'),
        ]);
    }
}
