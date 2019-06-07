<?php

namespace App\Exports;

use App\Account;
use App\AccountMember;
use App\Voucher;
use DB;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;

class RedemptionPartExport implements FromCollection, WithHeadings, ShouldAutoSize
{
    use Exportable;

    public function __construct($type, $id, $from, $to, $des)
    {
        $this->type = $type;
        $this->id = $id;
        $this->from = $from;
        $this->to = $to;
        $this->des = $des;
    }

    public function headings(): array
    {
        return ['Card Number', 'Date Issued', 'Status', 'Valid From', 'Valid To'];
    }

    public function collection()
    {
        $type = $this->type;
        $id = $this->id;
        $from = $this->from;
        $to = $this->to;
        $des = $this->des;

        $query = Voucher::query();

        if ($type == 'byAccount') {

            $query = $query->select('card_number', 'date_issued', 'status', 'valid_from', 'valid_to');
            $query = $query->where('account_id', $id);

        } else {

            $accountMember = AccountMember::where('member_id', $id)->pluck('account_id')->toArray();
            $accounts = Account::whereIn('id', $accountMember)->pluck('id')->toArray();

            $query = $query->select('card_number', 'date_issued', 'status', 'valid_from', 'valid_to');
            $query = $query->whereIn('account_id', $accounts);
        }

        $query = $query->whereBetween(DB::raw("YEAR(date_issued)"), [$from, $to]);

        if ($des != 'all') {
            $query = $query->where('destination_id', $des);
        }

        $query = $query->join('accounts', 'accounts.id', 'vouchers.account_id');
        $query = $query->join('destinations', 'destinations.id', 'vouchers.destination_id');

        $vouchers = $query->get();
        return $vouchers;
    }
}
