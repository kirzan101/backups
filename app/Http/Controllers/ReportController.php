<?php
namespace App\Http\Controllers;

use App\Account;
use App\AccountMember;
use App\Destination;
use App\Exports\AccountsExport;
use App\Exports\MembersExport;
use App\Exports\CollectionExport;
use App\Exports\RedemptionExport;
use App\Exports\RedemptionPartExport;
use App\Exports\ValidityExport;
use App\Exports\VouchersExport;
use App\Member;
use App\Payment;
use App\Voucher;
use DB;
use Illuminate\Http\Request;
use PDF;
use Yajra\Datatables\Datatables;

class ReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('reports.index');
    }

    public function getAccounts()
    {
        $payments = Payment::select(DB::raw("MONTHNAME(payment_date) as month, SUM(amount) as total"))
            ->where(DB::raw("YEAR(payment_date)"), date('Y'))
            ->groupBy('month')
            ->orderBy('month', 'desc')
            ->get();

        return \Response::json($payments, 200);
    }

    public function getMembers()
    {
        $members = DB::table('members')
            ->select('members.*', 'membership_types.type', 'invoices.*')
            ->join('membership_types', 'membership_types.id', 'members.membership_type')
            ->join('invoices', 'invoices.member_id', 'members.id')
            ->orderBy('members.id', 'desc')
            ->limit(10)
            ->get();

        return \Response::json($members, 200);
    }

    public function getVouchers()
    {
        $vouchers = DB::table('vouchers')
            ->join('members', 'members.id', 'vouchers.member_id')
            ->orderBy('vouchers.created_at', 'desc')
            ->limit(10)
            ->get();

        return \Response::json($vouchers, 200);
    }

    public function allMembers()
    {
        $portal = session('portal');

        $members = DB::select("SELECT DISTINCT m.id, ac.sales_deck, m.first_name, m.middle_name, m.last_name, m.status, m.birthday, m.gender, cn.contact_number, a.complete_address, e.email_address, COUNT(v.id) as voucherCount FROM members m
        INNER JOIN contact_numbers cn on cn.member_id = m.id
        INNER JOIN emails e ON e.member_id = m.id
        INNER JOIN addresses a ON a.member_id = m.id
        LEFT JOIN account_member am ON m.id = am.member_id
        INNER JOIN accounts ac ON ac.id = am.account_id
        LEFT JOIN vouchers v ON v.account_id = am.account_id
        WHERE m.membership_type = ? 
        GROUP BY m.id" , [$portal]);

        return Datatables::of($members)->make(true);
    }

    public function dTAccounts() {

        $query = Account::query();

        $m_type = session('portal');

        $query->select(DB::raw("accounts.id, GROUP_CONCAT(DISTINCT CONCAT(members.first_name, ' ', members.last_name)) m_name, consultants.name as c_name, destinations.destination_name,
        CAST(COUNT(vouchers.id) / COUNT(DISTINCT account_member.id) AS UNSIGNED) total,
        CAST(COUNT(IF(vouchers.status = 'unused',1,NULL)) / COUNT(DISTINCT account_member.id) AS UNSIGNED) unused,
        CAST(COUNT(IF(vouchers.status = 'redeemed',1,NULL)) / COUNT(DISTINCT account_member.id) AS UNSIGNED) redeemed,
        CAST(COUNT(IF(vouchers.status = 'canceled',1,NULL)) / COUNT(DISTINCT account_member.id) AS UNSIGNED) canceled,
        CAST(COUNT(IF(vouchers.status = 'forfeited',1,NULL)) / COUNT(DISTINCT account_member.id) AS UNSIGNED) forfeited"));

        $query->join('account_member', 'account_member.account_id', 'accounts.id');
        $query->join('members', 'members.id', 'account_member.member_id');
        $query->join('consultants', 'consultants.id', 'accounts.consultant_id');
        $query->join('vouchers', 'vouchers.account_id', 'accounts.id')->join('destinations','vouchers.destination_id','destinations.id');
        $query->where('accounts.membership_type', $m_type);

        $query->orderBy('id', 'desc');
        $query->groupBy('accounts.id');

        return Datatables::of($query)->make(true);  
    }

    public function dTVouchers() {

        $query = Voucher::query();

        $m_type = session('portal');

        $query->whereHas('account', function ($query) use ($m_type) {
            $query->where('membership_type', $m_type);
        });

        $query->with(['account.members', 'destination']);

        return Datatables::of($query)->make(true);
    }

    public function collection(Request $request)
    {
        if ($request->has('date_from')) {
            $date_from = $request->input('date_from');
        } else {
            $date_from = date('Y-01-01');
        }

        if ($request->has('date_to')) {
            $date_to = $request->input('date_to');
        } else {
            $date_to = date('Y-12-31');
        }

        $portal = session('portal');

        $collection = Payment::select(DB::raw("MONTHNAME(payment_date) as month, YEAR(payment_date) as year, SUM(amount) as total"))
            ->whereHas('invoice.account.members', function ($q) use ($portal) {
                $q->where('members.membership_type', $portal);
            })
            ->whereBetween('payment_date', [$date_from, $date_to])
            ->groupBy('month', 'year')
            ->orderBy('year', 'asc')
            ->orderBy(DB::raw('MONTH(payment_date)'), 'asc')
            ->get();

        $grand_total = 0;
        foreach ($collection as $a) {
            $grand_total += $a->total;
        }

        return view('reports.collection', compact('collection', 'date_from', 'date_to', 'grand_total'));
    }

    public function members()
    {
        return view('reports.members');

    }

    public function accounts(Request $request)
    {
        $query = Account::query();

        if ($request->has('per_page')) {
            $per_page = $request->input('per_page');
        } else {
            $per_page = 10;
        }

        $m_type = session('portal');

        $query->select(DB::raw("accounts.id, GROUP_CONCAT(DISTINCT CONCAT(members.first_name, ' ', members.last_name)) m_name, consultants.name as c_name,
        CAST(COUNT(vouchers.id) / COUNT(DISTINCT account_member.id) AS UNSIGNED) total,
        CAST(COUNT(IF(vouchers.status = 'unused',1,NULL)) / COUNT(DISTINCT account_member.id) AS UNSIGNED) unused,
        CAST(COUNT(IF(vouchers.status = 'redeemed',1,NULL)) / COUNT(DISTINCT account_member.id) AS UNSIGNED) redeemed,
        CAST(COUNT(IF(vouchers.status = 'canceled',1,NULL)) / COUNT(DISTINCT account_member.id) AS UNSIGNED) canceled,
        CAST(COUNT(IF(vouchers.status = 'forfeited',1,NULL)) / COUNT(DISTINCT account_member.id) AS UNSIGNED) forfeited"));

        $query->join('account_member', 'account_member.account_id', 'accounts.id');
        $query->join('members', 'members.id', 'account_member.member_id');
        $query->join('consultants', 'consultants.id', 'accounts.consultant_id');
        $query->join('vouchers', 'vouchers.account_id', 'accounts.id');

        $query->where('accounts.membership_type', $m_type);

        $destination = $request->destination;
        if ($request->has('destination') && $destination != 'all') {
            $query->where('vouchers.destination_id', $destination);
        } else {
            $destination = 'all';
        }

        $query->orderBy('id', 'desc');
        $query->groupBy('accounts.id');

        $accounts = $query->paginate($per_page);

        $destinations = Destination::all();

        return view('reports.accounts', compact('accounts', 'per_page', 'destination', 'destinations'));
    }

    public function vouchers(Request $request)
    {
        $query = Voucher::query();
        
        if ($request->has('per_page')) {
            $per_page = $request->input('per_page');
        } else {
            $per_page = 10;
        }
        
        if ($request->has('status') && $request->status != 'all') {
            $status = $request->status;
            $query = $query->where('status', $status);
        } else {
            $status = 'all';
        }
        
        if ($request->has('date_from') && $request->has('date_to')) {
            $date_from = $request->input('date_from');
            $date_to = $request->input('date_to');
            
            $query->where('date_issued', '>=', $date_from);
            $query->where('date_issued', '<=', $date_to);
        } else {
            $date_from = date('Y-m-d');
            $date_to = $date_from;
        }
        
        if ($request->has('account') && $request->input('account') != null && $request->input('account') != 'all') {
            $account_selected = $request->input('account');
            $query->where('account_id', $account_selected);
            $account_input = $request->input('account_input');
        } else {
            $account_selected = 'all';
            $account_input = null;
        }

        $destination = $request->input('destination');
        if ($request->has('destination') && $destination != 'all') {
            $query->where('destination_id', $destination);
        }

        $m_type = session('portal');

        $query->whereHas('account', function ($query) use ($m_type) {
            $query->where('membership_type', $m_type);
        });

        $query->with(['account.members', 'destination']);
        $query->orderBy('id', 'desc');

        $vouchers = $query->paginate($per_page);
        $accounts = Account::where('membership_type', $m_type)->with('members')->get();
        $destinations = Destination::all();

        return view('reports.vouchers', compact('vouchers', 'per_page', 'status', 'date_from', 'date_to', 'accounts', 'account_selected', 'account_input', 'destinations', 'destination'));
    }

    public function redemption(Request $request)
    {
        $query = Voucher::query();

        if (!$request->has('reportType')) {
            $report_type = 'monthly';
            $year = date('Y');
            $year_from = $year;
            $year_to = $year;
        } else {
            $report_type = $request->reportType;
            $year = $request->year;
            $year_from = $request->year_from;
            $year_to = $request->year_to;
        }

        if ($report_type == 'monthly') {
            $query = $query->select(DB::raw("destination_id, COUNT(id) as vouchers, MONTHNAME(date_issued) as month, COUNT(IF(status='unused',1,NULL)) 'unused', COUNT(IF(status='redeemed',1,NULL)) 'redeemed', COUNT(IF(status='canceled',1,NULL)) 'canceled', COUNT(IF(status='forfeited',1,NULL)) 'forfeited'"));
            $query = $query->where(DB::raw("YEAR(date_issued)"), $year);
            $query = $query->groupBy(DB::raw("MONTH(date_issued)"), 'destination_id');

            $view = 'reports.redemption.base_table';
            $data = array('vouchers', 'accounts', 'members', 'report_type', 'year', 'year_from', 'year_to', 'destination', 'destinations', 'account_selected', 'account_input');

            //Other than monthly report type
        } else {

            $request->validate([
                'year_from' => 'required',
                'year_to' => 'required|gte:year_from',
            ]);

            //By year
            if ($report_type == 'yearly') {

                $query = $query->select(DB::raw("destination_id, COUNT(id) as vouchers, YEAR(date_issued) as year, MONTHNAME(date_issued) as month, COUNT(IF(status='unused',1,NULL)) 'unused', COUNT(IF(status='redeemed',1,NULL)) 'redeemed', COUNT(IF(status='canceled',1,NULL)) 'canceled', COUNT(IF(status='forfeited',1,NULL)) 'forfeited'"));

                $query = $query->groupBy(DB::raw("YEAR(date_issued)"), DB::raw("MONTH(date_issued)"), 'destination_id');

                $view = 'reports.redemption.base_table';
                $data = array('vouchers', 'accounts', 'members', 'report_type', 'year', 'year_from', 'year_to', 'destination', 'destinations', 'account_selected', 'account_input');

                //By account
            } else if ($report_type == 'byAccount') {
                $request->validate([
                    'account' => 'required|exists:accounts,id',
                ]);

                $query = $query->select('*');
                $query = $query->where('account_id', $request->account);

                $account_selected = $request->input('account');
                $account_input = $request->input('account_input');

                $view = 'reports.redemption.by_account';
                $data = array('vouchers', 'accounts', 'members', 'report_type', 'year', 'year_from', 'year_to', 'destination', 'destinations', 'account_selected', 'account_input');

                //By member
            } else {

                $request->validate([
                    'member' => 'required|exists:members,id',
                ]);

                $accountMember = AccountMember::where('member_id', $request->member)->pluck('account_id')->toArray();
                $accounts = Account::whereIn('id', $accountMember)->pluck('id')->toArray();

                $query = $query->select('*');
                $query = $query->whereIn('account_id', $accounts);

                $member_input = $request->member_input;
                $member_selected = $request->member;

                $view = 'reports.redemption.by_member';
                $data = array('vouchers', 'accounts', 'members', 'report_type', 'year', 'year_from', 'year_to', 'destination', 'destinations', 'member_input', 'member_selected');
            }

            $query = $query->whereBetween(DB::raw("YEAR(date_issued)"), [$year_from, $year_to]);
        }

        $destination = $request->destination;
        if ($request->has('destination') && $destination != 'all') {
            $query = $query->where('destination_id', $destination);
        } else {
            $destination = 'all';
        }

        $m_type = session('portal');

        $query = $query->whereHas('account', function ($query) use ($m_type) {
            $query->where('membership_type', $m_type);
        });

        $query = $query->with(['account.members', 'destination']);

        $vouchers = $query->get();
        $accounts = Account::where('membership_type', $m_type)->with('members')->get();
        $members = Member::where('membership_type', $m_type)->orderBy('last_name', 'asc')->get();

        $destinations = Destination::all();

        return view($view, compact($data));
    }

    public function redemptionDetails($year, $month, $destination, $status)
    {
        $convMonth = strtotime($month);
        $m = date('m', $convMonth);

        $query = Voucher::query();

        $query->where(DB::raw("YEAR(date_issued)"), $year);
        $query->where(DB::raw("MONTH(date_issued)"), $m);
        $query->where('destination_id', $destination);
        $destination = Destination::where('id', $destination)->first();

        if ($status != 'all') {
            $query->where('status', $status);
        }

        $m_type = session('portal');
        $query = $query->whereHas('account', function ($query) use ($m_type) {
            $query->where('membership_type', $m_type);
        });

        $query->with('destination');
        $query->with('account.accountMember.member');

        $query->orderBy('date_issued', 'asc');

        $vouchers = $query->paginate(10);

        return view('reports.redemption.redemption_details', compact('vouchers', 'year', 'month', 'destination', 'status'));
    }

    public function validity(Request $request)
    {
            //    dd($request->all());
        $m_type = session('portal');
        
        $account_input = $request->input('account_input');
        $account_selected = $request->input('account');
        $date_from = $request->input('date_from');
        $date_to = $request->input('date_to');

        if ($request->has('per_page')) {
            $per_page = $request->input('per_page');
        } else {
            $per_page = 10;
        }

        $accounts = Account::where('membership_type', $m_type)->with('members')->get();

        $query = Voucher::query();

        $query->select('*');

        if ($request->has('account_input') && $account_input != null){
            $query->where('account_id', $account_selected);
        } else {
            $account_selected = 'all';
        }

        if (!$request->has('date_from')){
            $date_from = date('Y-m-d', strtotime('first day of january this year'));
        }

        if (!$request->has('date_to')){
            $date_to = date('Y-m-d');
        }

        $query->whereBetween('date_issued', [$date_from, $date_to]);

        $query->with('destination');
        $query->with('account.accountMember.member');
        $query->orderBy('date_issued', 'desc');
        $query->orderBy('account_id', 'asc');

        $vouchers = $query->paginate($per_page);

        return view('reports.validity', compact('vouchers', 'account_input', 'accounts', 'account_selected', 'date_from', 'date_to', 'per_page'));
    }

    public function exportCollectionExcel($from, $to)
    {
        $date = date('Y-m-d');

        return (new CollectionExport($from, $to))->download('collections_' . $date . '.xlsx');
    }

    public function exportAccountsExcel($destination)
    {
        $type = session('portal');
        $date = date('Y-m-d');

        return (new AccountsExport($type, $destination))->download('accounts_' . $date . '.xlsx');
    }

    public function exportMembersExcel($status)
    {
        $type = session('portal');
        $date = date('Y-m-d');

        return (new MembersExport($type, $status.""))->download('members_' . $date . '.xlsx');
    }

    public function exportVouchersExcel($status, $from, $to, $account, $destination)
    {
        $type = session('portal');

        return (new VouchersExport($type, $status, $from, $to, $account, $destination))->download('vouchers_' . $from . ' - ' . $to . '.xlsx');
    }

    public function exportRedemptionExcel($type, $year, $from, $to, $des)
    {
        $portal = session('portal');
        $date = date('Y-m-d');

        return (new RedemptionExport($portal, $type, $year, $from, $to, $des))->download('redemption_' . $date . '.xlsx');
    }

    public function exportRedemptionPartExcel($type, $id, $from, $to, $des)
    {
        $date = date('Y-m-d');
        return (new RedemptionPartExport($type, $id, $from, $to, $des))->download('redemption_' . $date . '.xlsx');
    }

    public function exportValidityExcel($id, $from, $to)
    {
        $date = date('Y-m-d');
        return (new ValidityExport($id, $from, $to))->download('validity_' . $id . '_' . $date . '.xlsx');
    }

    public function exportCollectionPdf($data)
    {
        $data = json_decode($data);
        $grand_total = 0;
        foreach ($data as $a) {
            $grand_total += $a->total;
        }

        $dateTo = end($data);
        $dateFrom = reset($data);

        $fromMonth = $dateFrom->month;
        $fromYear = $dateFrom->year;
        $toMonth = $dateTo->month;
        $toYear = $dateTo->year;

        $pdf = PDF::loadView('pdf.collection', compact('data', 'grand_total', 'fromMonth', 'fromYear', 'toMonth', 'toYear'));
        $date = date('Y-m-d');

        return $pdf->setPaper('a4','landscape')->download('collections_' . $date . '.pdf');
    }

    //MEMBERS
    public function exportMembersPdf($status)
    {
        set_time_limit(0);

        $query = Member::query();
        
        $m_type = session('portal');
        
        $query->with('contactNumbers');
        $query->with('email');
        $query->with('addresses');
        $query->where('membership_type',$m_type);
        // $query->where('middle_name', 'C.'); //QUERY FILTER
        $query->orderBy('id', 'desc');
        $members = $query->get(); //data
        
        $getType = DB::table('membership_types')->select('type')->where('id', $m_type)->value('type');
        $type = $getType != null ? $getType : 'All';
        
        $pdf = PDF::loadView('pdf.members', compact('members', 'type'));
        $pdf->setPaper('legal', 'landscape');
        $date = date('Y-m-d');

        return $pdf->stream();
        
        // return $pdf->download('members_' . $date . '.pdf');

    }

    //END OF MEMEBERs

    public function exportAccountsPdf($destination)
    {
        set_time_limit(0);
        $m_type = session('portal');
        $query = Account::query();

        $query->select(DB::raw("accounts.id, GROUP_CONCAT(DISTINCT CONCAT(members.first_name, ' ', members.last_name)) m_name, consultants.name as c_name,
        CAST(COUNT(vouchers.id) / COUNT(DISTINCT account_member.id) AS INT) total,
        CAST(COUNT(IF(vouchers.status = 'unused',1,NULL)) / COUNT(DISTINCT account_member.id) AS INT) unused,
        CAST(COUNT(IF(vouchers.status = 'redeemed',1,NULL)) / COUNT(DISTINCT account_member.id) AS INT) redeemed,
        CAST(COUNT(IF(vouchers.status = 'canceled',1,NULL)) / COUNT(DISTINCT account_member.id) AS INT) canceled,
        CAST(COUNT(IF(vouchers.status = 'forfeited',1,NULL)) / COUNT(DISTINCT account_member.id) AS INT) forfeited"));

        $query->join('account_member', 'account_member.account_id', 'accounts.id');
        $query->join('members', 'members.id', 'account_member.member_id');
        $query->join('consultants', 'consultants.id', 'accounts.consultant_id');
        $query->join('vouchers', 'vouchers.account_id', 'accounts.id');
        $query->where('accounts.membership_type', $m_type);

        if ($destination != 'all') {
            $query->where('vouchers.destination_id', $destination);
        }

        $query->orderBy('id', 'desc');
        $query->groupBy('accounts.id');

        $accounts = $query->get();

        $getType = DB::table('membership_types')->select('type')->where('id', $m_type)->value('type');
        $type = $getType != null ? $getType : 'All';

        $pdf = PDF::loadView('pdf.accounts', compact('accounts', 'type'));
        $date = date('Y-m-d');

        return $pdf->download('accounts_' . $date . '.pdf');
    }


    public function exportVouchersPdf($status, $from, $to, $account, $destination)
    {
 
        $query = Voucher::query();

        $m_type = session('portal');

        $query->whereHas('account', function ($query) use ($m_type) {
            $query->where('membership_type', $m_type);
        });

        $query->where('date_issued', '>=', $from);
        $query->where('date_issued', '<=', $to);

        if ($status != 'all') {
            $query->where('status', $status);
        }

        if ($account != 'all') {
            $query->where('account_id', $account);
        }

        if ($destination != 'all') {
            $query->where('destination_id', $destination);
        }

        $query->with(['account.members', 'destination']);
        $query->orderBy('id', 'desc');

        $getType = DB::table('membership_types')->select('type')->where('id', $m_type)->value('type');
        $type = $getType != null ? $getType : 'All';

        $vouchers = $query->get();

        $pdf = PDF::loadView('pdf.vouchers', compact('vouchers', 'type', 'status', 'from', 'to'));

        return $pdf->download('vouchers_' . $from . ' - ' . $to . '.pdf');
    }

    public function exportRedemptionPdf($type, $year, $from, $to, $des)
    {
        $query = Voucher::query();

        if ($type == 'monthly') {
            $query = $query->select(DB::raw("destination_id, COUNT(id) as vouchers, MONTHNAME(date_issued) as month, COUNT(IF(status='unused',1,NULL)) 'unused', COUNT(IF(status='redeemed',1,NULL)) 'redeemed', COUNT(IF(status='canceled',1,NULL)) 'canceled', COUNT(IF(status='forfeited',1,NULL)) 'forfeited'"));
            $query = $query->where(DB::raw("YEAR(date_issued)"), $year);
            $query = $query->groupBy(DB::raw("MONTH(date_issued)"));

        } else {

            $query = $query->select(DB::raw("destination_id, COUNT(id) as vouchers, YEAR(date_issued) as year, MONTHNAME(date_issued) as month, COUNT(IF(status='unused',1,NULL)) 'unused', COUNT(IF(status='redeemed',1,NULL)) 'redeemed', COUNT(IF(status='canceled',1,NULL)) 'canceled', COUNT(IF(status='forfeited',1,NULL)) 'forfeited'"));
            $query = $query->whereBetween(DB::raw("YEAR(date_issued)"), [$from, $to]);
            $query = $query->groupBy(DB::raw("YEAR(date_issued)"), DB::raw("MONTH(date_issued)"));
        }

        $m_type = session('portal');

        $query = $query->whereHas('account', function ($query) use ($m_type) {
            $query->where('membership_type', $m_type);
        });

        if ($des != 'all') {
            $query = $query->where('destination_id', $des);
        }

        $query = $query->with(['account.members', 'destination']);
        $vouchers = $query->get();

        $pdf = PDF::loadView('pdf.redemption', compact('vouchers', 'type', 'year', 'from', 'to', 'des'));
        $date = date('Y-m-d');

        return $pdf->download('redemption_' . $date . '.pdf');
    }

    public function exportRedemptionPartPdf($type, $id, $from, $to, $des)
    {
        $query = Voucher::query();

        if ($type == 'byAccount') {

            $query = $query->select('*');
            $query = $query->where('account_id', $id);

            $data = array('vouchers', 'type', 'id', 'year', 'from', 'to', 'des');

        } else {

            $accountMember = AccountMember::where('member_id', $id)->pluck('account_id')->toArray();
            $accounts = Account::whereIn('id', $accountMember)->pluck('id')->toArray();

            $query = $query->select('*');
            $query = $query->whereIn('account_id', $accounts);
            
            $member = Member::where('id', $id)->pluck('first_name', 'last_name')->first();
            $data = array('vouchers', 'type', 'member', 'id', 'year', 'from', 'to', 'des');
        }

        $query = $query->whereBetween(DB::raw("YEAR(date_issued)"), [$from, $to]);

        if ($des != 'all') {
            $query = $query->where('destination_id', $des);
        }

        $query = $query->with(['account.members', 'destination']);

        $vouchers = $query->get();

        $pdf = PDF::loadView('pdf.redemption_part', compact($data));
       
        $date = date('Y-m-d');

        return $pdf->download('redemption_' . $date . '.pdf');
    }

    public function exportValidityPdf($id, $from, $to)
    {
        $query = Voucher::query();

        $query->select('*');

        if ($id != 'all'){
            $query->where('account_id', $id);
        }

        $query->whereBetween('date_issued', [$from, $to]);

        $query->with('destination');
        $query->with('account.accountMember.member');
        $query->orderBy('date_issued', 'desc');
        $query->orderBy('account_id', 'asc');

        $vouchers = $query->get();
        $pdf = PDF::loadView('pdf.validity', compact('vouchers', 'from', 'to'));       

        return $pdf->setPaper('a4','landscape')->download('validity_' . $id . '_' . $from . ' - ' . $to . '.pdf');
    }
}
