<?php

namespace App\Exports;

use App\Voucher;
use DB;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;

class RedemptionExport implements FromCollection, WithHeadings, ShouldAutoSize
{
    use Exportable;

    public function __construct($portal, $type, $year, $from, $to, $des)
    {
        $this->portal = $portal;
        $this->type = $type;
        $this->year = $year;
        $this->from = $from;
        $this->to = $to;
        $this->des = $des;
    }

    public function headings(): array
    {
        return ['Month', 'Destination', 'Total Vouchers', 'Unused', 'Redeemed', 'Canceled', 'Forfeited'];
    }

    public function collection()
    {
        $portal = $this->portal;
        $type = $this->type;
        $year = $this->year;
        $from = $this->from;
        $to = $this->to;
        $des = $this->des;

        $query = Voucher::query();

        if ($type == 'monthly') {
            $query = $query->select(DB::raw("MONTHNAME(date_issued) as month, destinations.destination_name, COUNT(vouchers.id) as vouchers, COUNT(IF(status='unused',1,NULL)) 'unused', COUNT(IF(status='redeemed',1,NULL)) 'redeemed', COUNT(IF(status='canceled',1,NULL)) 'canceled', COUNT(IF(status='forfeited',1,NULL)) 'forfeited'"));
            $query = $query->where(DB::raw("YEAR(date_issued)"), $year);
            $query = $query->groupBy(DB::raw("MONTH(date_issued)"));

        } else {

            $query = $query->select(DB::raw("CONCAT(MONTHNAME(date_issued), ' ', YEAR(date_issued)) as monthyear, destinations.destination_name, COUNT(vouchers.id) as vouchers, COUNT(IF(status='unused',1,NULL)) 'unused', COUNT(IF(status='redeemed',1,NULL)) 'redeemed', COUNT(IF(status='canceled',1,NULL)) 'canceled', COUNT(IF(status='forfeited',1,NULL)) 'forfeited'"));
            $query = $query->whereBetween(DB::raw("YEAR(date_issued)"), [$from, $to]);
            $query = $query->groupBy(DB::raw("YEAR(date_issued)"), DB::raw("MONTH(date_issued)"));
        }

        $query = $query->join('accounts', 'accounts.id', 'vouchers.account_id');
        $query = $query->where('accounts.membership_type', $portal);

        $query = $query->join('destinations', 'destinations.id', 'vouchers.destination_id');

        if ($des != 'all') {
            $query = $query->where('destination_id', $des);
        }

        $vouchers = $query->get();
        return $vouchers;
    }
}
