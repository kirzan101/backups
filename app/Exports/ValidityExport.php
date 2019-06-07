<?php

namespace App\Exports;

use App\Voucher;
use DB;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ValidityExport implements FromCollection, WithHeadings, ShouldAutoSize
{
    use Exportable;

    public function __construct($id, $from, $to)
    {
        $this->id = $id;
        $this->from = $from;
        $this->to = $to;
    }

    public function headings(): array
    {
        return ['Member(s)', 'Voucher Number', 'Status', 'Destination', 'Issued On', 'Redeemed On', 'Check In', 'Check Out', 'Valid Until'];
    }

    public function collection()
    {
        $id = $this->id;
        $from = $this->from;
        $to = $this->to;

        $query = Voucher::query();

        $query->select(DB::raw("CONCAT(members.first_name, ' ', members.last_name )"), 'card_number', 'vouchers.status', 'destinations.destination_name', 'date_issued', 'date_redeemed', 'check_in', 'check_out', 'valid_to');

        $query->join('accounts', 'vouchers.account_id', 'accounts.id');
        $query->join('account_member', 'accounts.id', 'account_member.account_id');
        $query->join('members', 'account_member.member_id', 'members.id');
        $query->join('destinations', 'vouchers.destination_id', 'destinations.id');

        if ($id != 'all') {
            $query->where('account_member.account_id', $id);
        }

        $query->whereBetween('date_issued', [$from, $to]);
        $query->orderBy('date_issued', 'desc');
        $query->orderBy('vouchers.account_id', 'asc');
        $query->groupBy('vouchers.id');

        $vouchers = $query->get();
        return $vouchers;
    }
}
