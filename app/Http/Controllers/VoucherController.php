<?php

namespace App\Http\Controllers;

use App\Account;
use App\AccountMember;
use App\Consultant;
use App\Destination;
use App\Http\Controllers\AuditLogController;
use App\Member;
use App\MembershipType;
use App\UserGroup;
use App\Voucher;
use Auth;
use Illuminate\Http\Request;

class VoucherController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');

        $this->middleware(function ($request, $next) {
            $user_group = Auth::user()->user_group;

            $getModuleAccess = UserGroup::where('id', $user_group)->value('modules_access');
            $getModulePerms = UserGroup::where('id', $user_group)->value('modules_permissions');
            $modules_access = explode(',', $getModuleAccess);
            $modules_permissions = explode(',', $getModulePerms);

            $key = array_search('4', $modules_access);
            if (strpos($modules_permissions[$key], 'u') !== false) {
                return $next($request);
            }

            return redirect('/vouchers');

        }, ['only' => ['edit']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // dd($request->all());
        $query = Voucher::query();
        $search = $request->input('search');
        $status = $request->input('status');

        if ($request->has('search') && $request->search != '') {
            $query->where('id', 'like', $search . '%');
            $query->orWhere('card_number', 'like', $search . '%');

            $query->orWhereHas('account.members', function ($q) use ($search) {
                $q->where('first_name', 'like', $search . '%');
                $q->orWhere('last_name', 'like', $search . '%');
            });
        }

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

        if ($request->has('status') && $status != "all") {
            $query = $query->where("status", $status);
        }

        $portal = session('portal');

        $query = $query->whereHas('account', function ($q) use ($portal, $search) {
            $q->where('membership_type', $portal);
        });

        $query = $query->with('account.accountMember.member');
        $query = $query->orderBy($sort, $dir);
        $vouchers = $query->paginate($per_page);

        $accounts = Account::where('membership_type', $portal)
            ->orderBy('id', 'desc')
            ->get();

        $user_group = Auth::user()->user_group;

        $getModuleAccess = UserGroup::where('id', $user_group)->value('modules_access');
        $getModulePerms = UserGroup::where('id', $user_group)->value('modules_permissions');
        $modules_access = explode(',', $getModuleAccess);
        $modules_permissions = explode(',', $getModulePerms);

        $key = array_search('4', $modules_access);
        if (strpos($modules_permissions[$key], 'c') !== false) {
            $canCreate = true;
        } else {
            $canCreate = false;
        }

        $destinations = Destination::all();

        return view('vouchers.index', compact('vouchers', 'search', 'per_page', 'accounts', 'canCreate', 'destinations', 'status'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if ($request->status == 'redeemed') {
            $date_redeemed = date('Y-m-d');
        } else {
            $date_redeemed = null;
        }

        $this->validate(
            $request,
            [
                'account' => 'required|exists:accounts,id',
                'card_number' => 'required|unique:vouchers|regex:/(^([A-Za-z0-9 ][-_.,]?)+$)+/',
                'date_issued' => 'required|date|before:tomorrow',
                'valid_from' => 'required|date|after_or_equal:date_issued',
                'valid_to' => 'required|date|after_or_equal:valid_from',
                'destination' => 'required',
                'remarks' => 'nullable|max:255',
            ],
            [
                'card_number.unique' => 'This card number is already existing. Please insert a different card number.',
            ]
        );

        $voucher = new Voucher;

        $voucher->account_id = Account::where('id', $request->account)->value('id');
        $voucher->card_number = $request->card_number;
        $voucher->status = 'unused';
        $voucher->date_issued = $request->date_issued;
        $voucher->date_redeemed = $date_redeemed;
        $voucher->valid_from = $request->valid_from;
        $voucher->valid_to = $request->valid_to;
        $voucher->destination_id = $request->destination;
        $voucher->remarks = $request->remarks;
        $voucher->created_by = Auth::user()->username;

        $voucher->save();

        $auditLog = new AuditLogController;
        $description = 'created voucher: ' . $voucher->id;
        $auditLog->store($description, 4, $request->post());

        return redirect('/vouchers')->with('message', 'Voucher has been added.');
    }

    public function show(Voucher $voucher)
    {
        $account = Account::where('id', $voucher->account_id)->first();
        $memberType = MembershipType::where('id', $account->membership_type)->first();
        $consultant = Consultant::where('id', $account->consultant_id)->first();
        $accountMember = AccountMember::where('account_id', $account->id)->pluck('member_id')->toArray();
        $members = Member::whereIn('id', $accountMember)->get();
        $voucher->load('destination');

        return view('vouchers.show', compact('voucher', 'account', 'memberType', 'consultant', 'members'));
    }

    public function edit(Voucher $voucher)
    {
        $account = Account::where('id', $voucher->account_id)->first();
        $memberType = MembershipType::where('id', $account->membership_type)->first();
        $consultant = Consultant::where('id', $account->consultant_id)->first();
        $accountMember = AccountMember::where('account_id', $account->id)->pluck('member_id')->toArray();
        $members = Member::whereIn('id', $accountMember)->get();
        $destinations = Destination::all();

        return view('vouchers.edit', compact('voucher', 'account', 'memberType', 'consultant', 'members', 'destinations'));
    }

    public function update(Request $request)
    {
        $this->validate(
            $request,
            [
                'date_issued' => 'required|date',
                'status' => 'required',
                'valid_from' => 'required|date|after_or_equal:date_issued',
                'valid_to' => 'required|date|after_or_equal:valid_from',
                'destination' => 'required',
                'date_redeemed' => 'required_if:status,redeemed|nullable|date|after_or_equal:valid_from|before_or_equal:valid_to',
                'check_in_date' => 'required_if:status,redeemed|nullable|date|after_or_equal:date_redeemed',
                'check_in_time' => 'required_if:status,redeemed|nullable|date_format:H:i',
                'check_out_date' => 'required_if:status,redeemed|nullable|date|after_or_equal:check_in_date',
                'check_out_time' => 'required_if:status,redeemed|nullable|date_format:H:i|check_out',
                'guest_first_name' => 'required_with:guest_last_name|nullable|regex:/^[\pL\s\-]+$/u|min:2|max:50',
                'guest_middle_name' => 'nullable|regex:/^[\pL\s\-]+$/u|min:2|max:50',
                'guest_last_name' => 'required_with:guest_first_name|nullable|regex:/^[\pL\s\-]+$/u|min:2|max:50',
                'remarks' => 'nullable|max:255',
            ],
            [
                'date_redeemed.required_if' => 'The date redeemed field is required.',
                'guest_first_name.required_with' => 'The first name field is required.',
                'guest_last_name.required_with' => 'The last name field is required.',
            ]
        );

        $voucher = Voucher::find($request->id);

        $voucher->date_issued = $request->date_issued;
        $voucher->status = $request->status;
        $voucher->valid_from = $request->valid_from;
        $voucher->valid_to = $request->valid_to;
        $voucher->destination_id = $request->destination;

        if ($request->status == 'redeemed') {
            $voucher->date_redeemed = $request->date_redeemed;

            $voucher->check_in = date('Y-m-d H:i:s', strtotime($request->check_in_date . ' ' . $request->check_in_time));
            $voucher->check_out = date('Y-m-d H:i:s', strtotime($request->check_out_date . ' ' . $request->check_out_time));

            if ($request->check_guest == 'on'){
                $voucher->guest_first_name = $request->guest_first_name;
                $voucher->guest_middle_name = $request->guest_middle_name;
                $voucher->guest_last_name = $request->guest_last_name;
            }
        }

        $voucher->remarks = $request->remarks;
        $voucher->updated_by = Auth::user()->username;

        $voucher->save();

        $auditLog = new AuditLogController;
        $description = 'updated voucher: ' . $request->id;
        $auditLog->store($description, 4, $request->post());

        return redirect('/vouchers/' . $request->id)->with('message', 'Voucher has been updated.');
    }
    public function destroy($id)
    {
        $voucher->delete();
  
        return redirect()->route('vouchers.index')
                        ->with('success','Voucher successfully deleted');
    }
}
