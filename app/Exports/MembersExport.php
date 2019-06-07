<?php
namespace App\Exports;

use App\Member;
use DB;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;

class MembersExport implements FromCollection, WithHeadings, ShouldAutoSize
{
    use Exportable;

     public function __construct($type, $status)
    {
        $this->type = $type;
        $this->status = $status;


    }

    public function headings(): array
    {
        return ['ID', 'Sales Deck', 'Full Name', 'Status', 'Birthday', 'Gender', 'Contact No.', 'Address', 'Email', 'Unused Vouchers'];
    }

    public function collection()
    {
        
        $m_type = $this->type;
        $status = $this->status;
        
        $query = Member::query();
        
        
        $query->with('contactNumbers');
        $query->with('email');
        $query->with('addresses');
        $query->where('membership_type', $m_type); 
        
        if ($status != 'all') {
            $query->where('members.status', $status);
        }
        
        $query->orderBy('id', 'desc');
        // dd($status);
        return $query->get();
    }
}
