<?php

namespace App\Exports;

use App\Models\TerminalDataReceiveArchives;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;

class TerminalReceiveArchivedDataExport implements FromQuery, WithHeadings
{
    use Exportable;


    public function query()
    {
        return TerminalDataReceiveArchives::select('id','reg_no','terminal_data','shutter_sensor_status','smoke_sensor_status','gas_sensor_status','motion_sensor_status','created_at')->orderBy('id');

    }

    public function headings(): array
    {
        return [
            '#',
            'Reg.No',
            'Terminal Data',
            'Shutter',
            'Smoke',
            'Gas',
            'Motion',
            'Received At'
        ];
    }
}
