<?php

namespace App\Exports;

use App\Account;
use DB;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;

class AccountsExport implements FromCollection, WithHeadings, ShouldAutoSize
{
    use Exportable;

    public function __construct($type, $destination)
    {
        $this->type = $type;
        $this->destination = $destination;
    }

    public function headings(): array
    {
        return ['ID', 'Members', 'Consultant', 'Total Vouchers', 'Unused', 'Redeemed', 'Canceled', 'Forfeited'];
    }

    public function collection()
    {
        $m_type = $this->type;
        $destination = $this->destination;

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

        return $query->get();
    }
}
