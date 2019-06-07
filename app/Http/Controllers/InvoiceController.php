<?php

namespace App\Http\Controllers;

use App\Account;
use App\Http\Controllers\AuditLogController;
use App\Invoice;
use App\InvoiceDetails;
use App\Payment;
use App\UserGroup;
use Auth;
use DB;
use Illuminate\Http\Request;

class InvoiceController extends Controller
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

            $key = array_search('8', $modules_access);
            if (strpos($modules_permissions[$key], 'r') !== false) {
                return $next($request);
            }

            return redirect('/users');

        }, ['only' => ['invoices']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = Invoice::query();
        $search = $request->input('search');
        $portal = session('portal');

        $query->whereHas('account', function ($q) use ($portal) {
            $q->where('membership_type', $portal);
        });

        if ($request->has('search') && $request->search != '') {
            $query->whereHas('account', function ($q) use ($search) {
                $q->where('id', 'like', $search . '%');
            });

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
            $query = $query->orderBy($sort, $dir);
        } else {
            $query = $query->orderBy('created_at', 'desc');
        }

        $query = $query->with(['account.members']);

        $invoices = $query->paginate($per_page);

        $user_group = Auth::user()->user_group;

        $getModuleAccess = UserGroup::where('id', $user_group)->value('modules_access');
        $getModulePerms = UserGroup::where('id', $user_group)->value('modules_permissions');
        $modules_access = explode(',', $getModuleAccess);
        $modules_permissions = explode(',', $getModulePerms);

        $key = array_search('8', $modules_access);
        if (strpos($modules_permissions[$key], 'c') !== false) {
            $canCreate = true;
        } else {
            $canCreate = false;
        }

        return view('invoices.index', compact('invoices', 'search', 'per_page', 'canCreate'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $invoices = Invoice::with(['account.members', 'payments'])->find($id);

        return view('invoices.show', compact('invoices'));
    }

    public function create()
    {
        $portal = session('portal');
        $accounts = Account::where('membership_type', $portal)
            ->with('members')
            ->get();

        return view('invoices.create', compact('accounts'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'account' => 'required|exists:accounts,id',
            'invoice_date' => 'required|date',
            'due_at' => 'required|date|after_or_equal:invoice_date',
            'items' => 'required|array|min:1',
            'items.*' => 'required',
            'quantities' => 'required|array|min:1',
            'quantities.*' => 'required|numeric',
        ]);

        $invoice = new Invoice;

        $invoice->account_id = $request->account;
        $invoice->principal_amount = $request->total;
        $invoice->downpayment = 0;
        $invoice->total_paid_amount = 0;
        $invoice->remaining_balance = $request->total;
        $invoice->status = 'draft';
        $invoice->created_by = Auth::user()->username;

        $invoice->save();

        DB::table('invoices')->where('id', $invoice->id)->update(['invoice_number' => sprintf('%07d', $invoice->id)]);

        foreach ($request->items as $i => $item) {
            $invoiceDetails = new InvoiceDetails;

            $invoiceDetails->invoice_id = $invoice->id;
            $invoiceDetails->item = $request->items[$i];
            $invoiceDetails->quantity = $request->quantities[$i];
            $invoiceDetails->unit_price = $request->unit_prices[$i];
            $invoiceDetails->amount = $request->amounts[$i];

            $invoiceDetails->save();
        }

        $auditLog = new AuditLogController;
        $description = 'created invoice: ' . $invoice->id . ' for account: ' . $request->account . '.';
        $auditLog->store($description, 8, $request->post());

        return redirect('/invoices')->with('message', 'Invoice has been created.');
    }

    public function storePayment(Request $request)
    {
        $request->validate([
            'payment_date' => 'required|date',
            'amount' => 'required|numeric|gt:0|lte:balance',
            'percent_rate' => 'required|numeric|min:0|max:100',
            'comment' => 'nullable|string',
        ]);

        $payment = new Payment;

        $payment->invoice_id = $request->invoice_id;
        $payment->payment_date = $request->payment_date;
        $payment->amount = $request->amount;
        $payment->percent_rate = $request->percent_rate;
        $payment->comment = $request->comment;
        $payment->created_by = Auth::user()->username;

        $payment->save();

        $balance = $request->balance;
        $amount = $request->amount;
        $total_paid = $request->total_paid_amount;

        $new_balance = $balance - $amount;

        if ($new_balance <= 0) {
            $status = 'full';
        } elseif ($new_balance > 0 && $new_balance < $balance) {
            $status = 'partial';
        } else {
            $status = 'draft';
        }

        DB::table('invoices')->where('id', $request->invoice_id)
            ->update([
                'remaining_balance' => $new_balance,
                'total_paid_amount' => $total_paid + $request->amount,
                'status' => $status,
            ]);

        $auditLog = new AuditLogController;
        $description = 'created payment: ' . $payment->id . ' for invoice: ' . $request->invoice_id . '.';
        $auditLog->store($description, 5, $request->post());

        return redirect('/invoices/' . $request->invoice_id)->with('message', 'Payment has been added.');
    }
}
