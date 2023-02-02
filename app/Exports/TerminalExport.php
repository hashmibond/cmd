<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;

class TerminalExport implements FromQuery, WithHeadings
{

        use Exportable;

        protected $request;

        public function __construct($request)
        {
            $this->request = $request;
        }

        public function query()
        {
            return DB::table('terminal_actions')->select('terminal_actions.id','terminal_actions.reg_no','terminal_actions.imei','terminal_actions.status','terminal_actions.status_updated_at','terminal_actions.is_approved','terminal_actions.approved_at','terminal_actions.allocate_place','users.name as userName')
                ->leftJoin('users','users.id','=','terminal_actions.user_id')
                ->when($this->request->StartDate, function ($query) {
                    $query->where('terminal_actions.approved_at','>=', $this->request->StartDate . ' 00:00:00');
                })
                ->when($this->request->EndDate, function ($query) {
                    $query->where('terminal_actions.approved_at','<=', $this->request->EndDate . ' 23:59:59');
                })
                ->when($this->request->terminalId, function ($query) {
                    $query->where('terminal_actions.id', $this->request->terminalId);
                })
                ->orderBy('terminal_actions.id');

        }

        public function headings(): array
        {
            return [
                '#',
                'Reg.No',
                'IMEI',
                'Status',
                'Updated At',
                'Reg.Status',
                'Approved At',
                'Address',
                'User'
            ];
        }
    }

