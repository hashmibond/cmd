<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class InfiLoop extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:infinite-loop';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Testing Infinite Loop';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $testArr=['1','2','3','4','5','5','6','7','8','9','10','11','12','13','14','15','16','17','18','19','20','21','21','22','23','24','25','26','27','28','29','30'];
        //dd($testArr);
        while (true) {
            foreach ($testArr as $k=>$v){
                try {
                    if ($k==2 || $k==4 || $k==28){
                        DB::table('tests')->insert(['err_inde'=>$k]);//unknown column
                    }
                }catch (\Throwable $th){
                    DB::table('tests')->insert(['err_index'=>$k]);
                }
            }
        }
    }
}
