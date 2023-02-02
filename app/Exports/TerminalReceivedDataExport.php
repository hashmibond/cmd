<?php

namespace App\Exports;

use App\Models\TerminalDataReceive;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;

class TerminalReceivedDataExport implements FromQuery, WithHeadings
{
    use Exportable;

    protected $request;

    public function __construct($request)
    {
        $this->request = $request;
    }

    public function query()
    {
        return TerminalDataReceive::select('id','reg_no','terminal_data','shutter_sensor_status','smoke_sensor_status','gas_sensor_status','motion_sensor_status','created_at')->orderBy('id');

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
