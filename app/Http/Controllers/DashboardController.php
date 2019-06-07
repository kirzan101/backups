<?php

namespace App\Http\Controllers;

use App\Payment;
use DB;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $portal = session('portal');
        return view('dashboard.index');
    }

    public function getCollections($year)
    {
        $portal = session('portal');

        $months = array('January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December');

        $getCollections = Payment::select(DB::raw("MONTH(payment_date) as month, YEAR(payment_date) as year, amount"))
            ->whereHas('invoice.account.members', function ($q) use ($portal) {
                $q->where('members.membership_type', $portal);
            })
            ->where(DB::raw('YEAR(payment_date)'), $year)
            ->groupBy('month')
            ->orderBy(DB::raw('MONTH(payment_date)'), 'asc')
            ->get();

        $collections = array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);

        foreach ($getCollections as $c) {
            for ($i = 0; $i < 12; $i++) { //Loop through months
                if ($c->month == $i + 1) { //If collection is in the month
                    $collections[$i] += $c->amount;
                }
            }
        }

        $data = array(
            'labels' => $months,
            'collections' => $collections,
        );

        return \Response::json($data);
    }

    public function getVouchers($year)
    {
        $portal = session('portal');

        $issued_data = DB::table('vouchers')
            ->select(DB::raw('count(vouchers.id) as `issued`'), DB::raw("MONTH(date_issued) as month"))
            ->join('accounts', 'accounts.id', 'vouchers.account_id')
            ->where(DB::raw("YEAR(date_issued)"), $year)
            ->where('accounts.membership_type', $portal)
            ->groupBy('month')
            ->orderBy('date_issued', 'asc')
            ->get();

        $redeemed_data = DB::table('vouchers')
            ->select(DB::raw('count(vouchers.id) as `redeemed`'), DB::raw("MONTH(date_redeemed) as month"))
            ->join('accounts', 'accounts.id', 'vouchers.account_id')
            ->where(DB::raw("YEAR(date_issued)"), $year)
            ->where(DB::raw("MONTH(date_redeemed)"), date('m'))
            ->where('accounts.membership_type', $portal)
            ->groupBy('month')
            ->orderBy('date_redeemed', 'asc')
            ->get();

        $months = array('January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December');

        $issued = array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);
        $redeemed = $issued;

        foreach ($issued_data as $id) {
            for ($i = 0; $i < 12; $i++) { //Loop through months
                if ($id->month == $i + 1) { //If voucher is in the month
                    $issued[$i] += $id->issued;
                }
            }
        }

        foreach ($redeemed_data as $r) {
            for ($i = 0; $i < 12; $i++) { //Loop through months
                if ($r->month == $i + 1) { //If voucher is in the month
                    $redeemed[$i] += $r->redeemed;
                }
            }
        }

        $data = array(
            'labels' => $months,
            'issued' => $issued,
            'redeemed' => $redeemed,
        );

        return \Response::json($data);
    }

    public function getVoucherStatus()
    {
        $portal = session('portal');

        $unused = DB::table('vouchers')->select('status')->where('status', 'unused')->where('accounts.membership_type', $portal)->join('accounts', 'accounts.id', 'vouchers.account_id')->get();
        $redeemed = DB::table('vouchers')->select('status')->where('status', 'redeemed')->where('accounts.membership_type', $portal)->join('accounts', 'accounts.id', 'vouchers.account_id')->get();
        $canceled = DB::table('vouchers')->select('status')->where('status', 'canceled')->where('accounts.membership_type', $portal)->join('accounts', 'accounts.id', 'vouchers.account_id')->get();
        $forfeited = DB::table('vouchers')->select('status')->where('status', 'forfeited')->where('accounts.membership_type', $portal)->join('accounts', 'accounts.id', 'vouchers.account_id')->get();

        $status = array(
            'unused' => count($unused),
            'redeemed' => count($redeemed),
            'canceled' => count($canceled),
            'forfeited' => count($forfeited),
        );

        return \Response::json($status);
    }

    public function getAccountStatus()
    {
        $portal = session('portal');

        $active = DB::table('members')->select('status')->where('status', 'active')->where('membership_type', $portal)->get();
        $inactive = DB::table('members')->select('status')->where('status', 'inactive')->where('membership_type', $portal)->get();

        $status = array(
            'active' => count($active),
            'inactive' => count($inactive),
        );

        return \Response::json($status);
    }
}
