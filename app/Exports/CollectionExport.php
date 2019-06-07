<?php

namespace App\Exports;

use App\Payment;
use DB;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithHeadings;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class CollectionExport implements FromCollection, WithHeadings, ShouldAutoSize, WithColumnFormatting
{
    use Exportable;

    public function __construct($from, $to)
    {
        $this->from = $from;
        $this->to = $to;
    }

    public function headings(): array
    {
        return ['Year', 'Month', 'Amount'];
    }

    public function collection()
    {
        $portal = session('portal');

        return Payment::select(DB::raw("YEAR(payment_date) as year, MONTHNAME(payment_date) as month, SUM(amount) as total"))
            ->whereHas('invoice.account.members', function ($q) use ($portal) {
                $q->where('members.membership_type', $portal);
            })
            ->whereBetween('payment_date', [$this->from, $this->to])
            ->groupBy('month', 'year')
            ->orderBy('year', 'asc')
            ->orderBy(DB::raw('MONTH(payment_date)'), 'asc')
            ->get();
    }

    /**
     * @return array
     */

    public function columnFormats(): array
    {
        return ['C' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1];
    }
}
