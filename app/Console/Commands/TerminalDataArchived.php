<?php

namespace App\Console\Commands;

use App\Exports\TerminalReceiveArchivedDataExport;
use App\Models\TerminalDataReceive;
use App\Models\TerminalDataReceiveArchives;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
Use App\Jobs\TerminalData\TerminalDataArchiving;
Use App\Jobs\TerminalData\TerminalArchivedDataExporting;
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
        //dd(TerminalDataReceive::where('created_at','<','2023-01-23 00:00:00')->orderBy('id'));
        //dd(now()->modify("first day of last month")->format('Y-m-d').' 00:00:00');
        try {
            if (TerminalDataReceiveArchives::count()!=0){
                dispatch(new TerminalArchivedDataExporting());
            }
            if (TerminalDataReceive::where('created_at','<','2023-01-24 00:00:00')->count()!=0){
                DB::beginTransaction();
                dispatch(new TerminalDataArchiving());
                DB::commit();
            }
        }catch (\Throwable $th){
            DB::rollBack();
            //dd($th->getMessage());
        }
    }
}
