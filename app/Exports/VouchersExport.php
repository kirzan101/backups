<?php

namespace App\Exports;

use App\Voucher;
use DB;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;

class VouchersExport implements FromCollection, WithHeadings, ShouldAutoSize
{
    use Exportable;

    public function __construct($type, $status, $from, $to, $account, $destination)
    {
        $this->type = $type;
        $this->status = $status;
        $this->from = $from;
        $this->to = $to;
        $this->account = $account;
        $this->destination = $destination;
    }

    public function headings(): array
    {
        return ['Member Name', 'Voucher Number', 'Status', 'Destination', 'Issued On', 'Redeemed On', 'Check In', 'Check Out', 'Guest Name', 'Created By'];
    }

    public function collection()
    {
        $m_type = $this->type;
        $status = $this->status;
        $from = $this->from;
        $to = $this->to;
        $account = $this->account;
        $destination = $this->destination;

        $query = Voucher::query();

        $query->select(DB::raw("GROUP_CONCAT(CONCAT(members.first_name, ' ', members.last_name)), vouchers.card_number, vouchers.status, destinations.destination_name, vouchers.date_issued, vouchers.date_redeemed, vouchers.check_in, vouchers.check_out, CONCAT(vouchers.guest_first_name, ' ', guest_last_name)"));

        $query->join('accounts', 'accounts.id', 'vouchers.account_id');
        $query->join('destinations', 'destinations.id', 'vouchers.destination_id');
        $query->join('account_member', 'account_member.account_id', 'accounts.id');
        $query->join('members', 'members.id', 'account_member.member_id');
        $query->where('accounts.membership_type', $m_type);
        $query->where('date_issued', '>=', $from);
        $query->where('date_issued', '<=', $to);

        //filters
        if ($status != 'all') {
            $query->where('vouchers.status', $status);
        }

        if ($account != 'all' && $account != null) {
            $query->where('vouchers.account_id', $account);
        }

        if ($destination != 'all') {
            $query->where('vouchers.destination_id', $destination);
        }

        $query->orderBy('vouchers.id', 'desc');
        $query->groupBy('vouchers.id');

        $result = $query->get();

        return $result;
    }
}
