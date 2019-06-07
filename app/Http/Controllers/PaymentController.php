<?php

namespace App\Http\Controllers;

use App\Http\Controllers\AuditLogController;
use App\Invoice;
use App\Payment;
use App\UserGroup;
use Auth;
use DB;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $portal = session('portal');

        $search = $request->input('search');

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

        $payments = Payment::whereHas('invoice.account.members', function ($q) use ($search, $portal) {
            $q->where('members.membership_type', $portal);
            $q->where(function ($query) use ($search) {
                $query->where('members.first_name', 'like', '%' . $search . '%');
                $query->orWhere('members.last_name', 'like', '%' . $search . '%');
            });
        })
            ->orderBy($sort, $dir)
            ->paginate($per_page);

        $invoices = DB::table('invoices')
            ->select('invoices.*', 'accounts.id', 'members.*', 'account_member.*')
            ->join('accounts', 'accounts.id', '=', 'invoices.account_id')
            ->join('account_member', 'account_member.account_id', 'accounts.id')
            ->join('members', 'members.id', 'account_member.member_id')
            ->where('accounts.membership_type', $portal)
            ->orderBy('invoices.id', 'desc')
            ->groupBy('invoices.invoice_number')
            ->get();

        //dd($lists);

        $user_group = Auth::user()->user_group;

        $getModuleAccess = UserGroup::where('id', $user_group)->value('modules_access');
        $getModulePerms = UserGroup::where('id', $user_group)->value('modules_permissions');
        $modules_access = explode(',', $getModuleAccess);
        $modules_permissions = explode(',', $getModulePerms);

        $key = array_search('5', $modules_access);
        if (strpos($modules_permissions[$key], 'c') !== false) {
            $canCreate = true;
        } else {
            $canCreate = false;
        }

        return view('payments.index', compact('payments', 'search', 'per_page', 'invoices', 'canCreate', 'invoices'));
    }

    public function store(Request $request)
    {
        $invoice_id = Invoice::where('id', $request->invoice)->value('id');

        $remainingBalance = Invoice::where('id', $invoice_id)->value('remaining_balance');
        $principal_amount = Invoice::where('id', $invoice_id)->value('principal_amount');

        $this->validate($request, [
            'invoice_input' => 'required',
            'invoice' => 'required|exists:invoices,id',
            'payment_date' => 'required',
            'amount' => 'required|gt:0|lte:' . $remainingBalance,
            'percent_rate' => 'required|numeric',
            'comment' => 'nullable|string',
        ]);

        $payment = new Payment;

        $payment->invoice_id = $invoice_id;
        $payment->payment_date = $request->payment_date;
        $payment->amount = $request->amount;
        $payment->percent_rate = $request->percent_rate;
        $payment->comment = $request->comment;
        $payment->created_by = Auth::user()->username;

        $payment->save();

        //Invoice
        $amountToBePaid = $request->amount;
        $invoices = Invoice::where('id', $invoice_id);
        $invoices->increment('total_paid_amount', $amountToBePaid);
        $invoices->decrement('remaining_balance', $amountToBePaid);

        if ($remainingBalance - $amountToBePaid <= 0) {

            Invoice::where('id', $invoice_id)
                ->update(['status' => 'full']);

        } elseif ($remainingBalance < $principal_amount && $amountToBePaid > 0) {

            Invoice::where('id', $invoice_id)
                ->update(['status' => 'partial']);
        }

        $auditLog = new AuditLogController;
        $description = 'created payment: ' . $payment->id;
        $auditLog->store($description, 5, $request->post());

        return redirect('/payments')->with('message', 'Payment has been recorded.');
    }
}
