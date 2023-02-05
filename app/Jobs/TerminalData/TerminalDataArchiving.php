<?php

namespace App\Jobs\TerminalData;

use App\Models\TerminalDataReceive;
use App\Models\TerminalDataReceiveArchives;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class TerminalDataArchiving implements ShouldQueue
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
        Log::info('Terminal data Archive going to start');
        TerminalDataReceive::where('created_at','<','2023-01-24 00:00:00')->orderBy('id')
            ->chunk(100, function ($terminalDataReceives) {
                Log::info('Terminal data Archive chunk started');
                foreach ($terminalDataReceives as $terminalDataReceive) { /*if ($terminalDataReceive->id==10){TerminalDataReceiveArchives::insert();}*/TerminalDataReceiveArchives::insert($terminalDataReceive->toArray());}
                Log::info('Terminal data Archive chunk ended');
            });
        TerminalDataReceive::where('created_at','<','2023-01-24 00:00:00')->delete();
        Log::info('Terminal data Archive going to end');
    }
}
