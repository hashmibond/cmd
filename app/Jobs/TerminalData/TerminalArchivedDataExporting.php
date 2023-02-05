<?php

namespace App\Jobs\TerminalData;

use App\Exports\TerminalReceiveArchivedDataExport;
use App\Models\TerminalDataReceiveArchives;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;

class TerminalArchivedDataExporting implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Log::info('Terminal archive data going to export');
        Excel::store(new TerminalReceiveArchivedDataExport, "excels/terminalArchivedData/terminal_data_archived" . date('d_m_Y_H_i_s') . ".xlsx");
        TerminalDataReceiveArchives::truncate();
        Log::info('Terminal archive data exporting going to end');
    }
}
