<?php

namespace App\Exports;

use App\Visit;
use App\Institute;
use App\Branch;
use App\Customer;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithMapping;

class CustomerVisitExport implements FromQuery, WithMapping, ShouldAutoSize, WithHeadings, WithEvents
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function __construct(int $id)
    {
        $this->id = $id;
    }

    public function query()
    {
        return Visit::query()->where('customer_id', $this->id);
    }

    public function map($visit): array
    {
        return [
            $visit->id,
            $visit->customer->first_name . " " . $visit->customer->last_name,
            Branch::find($visit->branch_id)->name,
            Institute::find($visit->institute_id)->name,
            $visit->purpose,
            $visit->remarks,
            $visit->token_no,
            $visit->status,
            $visit->start_time,
            $visit->end_time,
        ];
    }

    public function headings(): array
    {
        return [
            '#',
            'Customer Fullname',
            'Branch',
            'Institute',
            'Purpose',
            'Remarks',
            'Token Number',
            'Status',
            'Start Time',
            'End Time',
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $event->sheet->getStyle('A1:J1')->applyFromArray([
                    'font' => [
                        'bold' => true,
                    ]
                    ]);
            }
        ];
    }
}
