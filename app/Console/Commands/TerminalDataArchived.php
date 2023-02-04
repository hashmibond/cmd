<?php

namespace App\Console\Commands;

use App\Exports\TerminalReceiveArchivedDataExport;
use App\Models\TerminalDataReceive;
use App\Models\TerminalDataReceiveArchives;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use PhpParser\Node\Stmt\TryCatch;

class TerminalDataArchived extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'terminal:data-archived';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Terminal data archived';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        //dd(now()->modify("first day of last month")->format('Y-m-d').' 00:00:00');
        try {
            if (TerminalDataReceiveArchives::count()!=0){
                Excel::store(new TerminalReceiveArchivedDataExport, "excels/terminalArchivedData/terminal_data_archived" . date('d_m_Y_H_i_s') . ".xlsx");
                TerminalDataReceiveArchives::truncate();
            }
            if (TerminalDataReceive::where('created_at','<',now()->modify("first day of last month")->format('Y-m-d').' 00:00:00')->count()!=0){
                DB::beginTransaction();
                TerminalDataReceive::where('created_at','<',now()->modify("first day of last month")->format('Y-m-d').' 00:00:00')->orderBy('id')
                    ->chunk(5000, function ($terminalDataReceives) {
                        foreach ($terminalDataReceives as $terminalDataReceive) { TerminalDataReceiveArchives::insert($terminalDataReceive->toArray());}
                    });
                TerminalDataReceive::where('created_at','<',now()->modify("first day of last month")->format('Y-m-d').' 00:00:00')->delete();
                DB::commit();
            }
        }catch (\Throwable $th){
            DB::rollBack();
            //dd($th->getMessage());
        }
    }
}
