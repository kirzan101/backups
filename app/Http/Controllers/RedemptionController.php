<?php

namespace App\Http\Controllers;

use App\Http\Controllers\AuditLogController;
use App\Voucher;
use Auth;
use DB;
use Illuminate\Http\Request;

class RedemptionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = Voucher::query();

        $search = $request->input('search');
        if ($request->has('search') && $request->search != '') {
            $query->where('id', 'like', $search . '%');
            $query->orWhere('card_number', 'like', $search . '%');
            $query->where('status', 'unused');

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

        $query->where('status', 'unused');

        $portal = session('portal');

        $query = $query->whereHas('account', function ($q) use ($portal, $search) {
            $q->where('membership_type', $portal);
        });

        $query = $query->with('account.accountMember.member');
        $query = $query->orderBy($sort, $dir);
        $redemptions = $query->paginate($per_page);

        $destinations = DB::table('destinations')->get();

        return view('redemptions.index', compact('redemptions', 'search', 'per_page', 'destinations'));
    }

    public function updateStatus(Request $request)
    {
        DB::table('vouchers')
            ->where('id', $request->id)
            ->update([
                'status' => $request->status,
                'updated_at' => date('Y-m-d H:i:s'),
            ]);

        $auditLog = new AuditLogController;
        $description = 'updated reservation: ' . $request->id;
        $auditLog->store($description, 6, $request->post());

        return redirect('/redemptions')->with('message', 'Redemption has been updated.');
    }

    public function redeemed(Request $request)
    {
        $voucher_id = $request->voucher_id;
        $valid_from = $request->valid_from;
        $valid_to = $request->valid_to;

        $this->validate(
            $request,
            [
                'voucher_id' => 'required',
                'valid_from' => 'required|date',
                'valid_to' => 'required|date',
                'date_redeemed' => 'required|date|after_or_equal:valid_from|before_or_equal:valid_to',
                'check_in_date' => 'required|date|after_or_equal:date_redeemed',
                'check_in_time' => 'required|date_format:H:i',
                'check_out_date' => 'required|date|after_or_equal:check_in_date',
                'check_out_time' => 'required|date_format:H:i|check_out',
                'guest_first_name' => 'nullable|required_if:check_guest,on|regex:/^[\pL\s\-]+$/u|min:2|max:50',
                'guest_middle_name' => 'nullable|regex:/^[\pL\s\-]+$/u|min:2|max:50',
                'guest_last_name' => 'nullable|required_if:check_guest,on|regex:/^[\pL\s\-]+$/u|min:2|max:50',
                'destination' => 'required',
            ],
            [
                'guest_first_name.required_if' => 'The guest first name is required.',
                'guest_last_name.required_if' => 'The guest last name is required.',
            ]
        );

        $voucher = Voucher::find($voucher_id);

        $voucher->status = 'redeemed';
        $voucher->date_redeemed = $request->date_redeemed;
        $voucher->check_in = date('Y-m-d H:i:s', strtotime($request->check_in_date . ' ' . $request->check_in_time));
        $voucher->check_out = date('Y-m-d H:i:s', strtotime($request->check_out_date . ' ' . $request->check_out_time));
        
        if ($request->check_guest == 'on'){
            $voucher->guest_first_name = $request->guest_first_name;
            $voucher->guest_middle_name = $request->guest_middle_name;
            $voucher->guest_last_name = $request->guest_last_name;
        }
        
        $voucher->destination_id = $request->destination;
        $voucher->updated_by = Auth::user()->username;

        $voucher->save();

        $auditLog = new AuditLogController;
        $description = 'updated voucher: ' . $request->voucher_id;
        $auditLog->store($description, 4, $request->post());

        return redirect('/redemptions')->with('message', 'Redemption has been updated.');
    }
}
