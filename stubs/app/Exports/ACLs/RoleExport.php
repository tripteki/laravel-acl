<?php

namespace App\Exports\ACLs;

use Spatie\Permission\Contracts\Role as RoleModel;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;

class RoleExport implements FromCollection, ShouldAutoSize, WithStyles, WithHeadings
{
    /**
     * @param \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet $sheet
     * @return array
     */
    public function styles(Worksheet $sheet)
    {
        return [

            1 => [ "font" => [ "bold" => true, ], ],
        ];
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [

            "Name",
        ];
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function collection()
    {
        return app(RoleModel::class)->all([

            "name",
        ]);
    }
};
