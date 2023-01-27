<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Terminal;
use App\Models\TerminalAction;
use App\Models\TerminalDataReceive;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TerminalDataReceiveController extends Controller
{

    public function terminalDataStore(Request $request)
    {
        try {
          	$data=self::manipulateRequestData($request->all());
            //dd($data[1][0]);
            DB::beginTransaction();

            /*$allRequest= explode(',',$request->all());*/

            TerminalDataReceive::create([
                'terminal_data' => $data[0],
                'reg_no' => $data[1][0],
                'shutter_sensor_status' => $data[1][2],
                'smoke_sensor_status' => $data[1][3],
                'gas_sensor_status' => $data[1][4],
                'motion_sensor_status' => $data[1][5],
            ]);

            $updateFlag=self::updateChecker($data[1]);
            if ($updateFlag[0]==1){
                /*----------update terminals and terminal_actions table-----------*/
                self::updateTerminalRelatedTable($data[1],$updateFlag);
            }

            /*$terminal_table_info= Terminal::where('reg_no',$request->A)->first();

            if ($terminal_table_info){
                Terminal::where('reg_no',$request->A)->first()->update([
                    'reg_no' => $terminal_table_info->reg_no,
                    'terminal_id' => $terminal_table_info->terminal_id,
                    'user_id' => $terminal_table_info->user_id,
                    'shutter_sensor_status' => $request->C,
                    'shutter_sensor_updated_at' => now(),
                    'smoke_sensor_status' => $request->D,
                    'smoke_sensor_updated_at' => now(),
                    'gas_sensor_status' => $request->E,
                    'gas_sensor_updated_at' => now(),
                    'motion_sensor_status' => $request->F,
                    'motion_sensor_updated_at' => now(),
                    'is_terminal_on' => 1,
                    'terminal_updated_at' => now(),
                ]);
            }*/
            DB::commit();
            return response()->json([
                'status' => true,
                'message' => 'successfully added'
            ], 200);
        } catch (\Throwable $th) {
            DB::rollback();
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    public static function manipulateRequestData(array $requestArr)
    {
        $dataStr="";
        $dataArr=[];
        foreach($requestArr as $k=>$v){
            $dataArr[] = $v;
            if (array_key_last($requestArr) === array_key_first($requestArr)) {
                $dataStr= $k .'='. $v;
            }
            else if ($k === array_key_first($requestArr)) {
                $dataStr= $k .'='. $v.' , ';
            }

            else if ($k === array_key_last($requestArr)) {
                $dataStr= $dataStr . $k .'='. $v;
            }
            else{
                $dataStr= $dataStr . $k .'='. $v .' , ' ;
            }
        }
        return [$dataStr,$dataArr];

    }

    public static function updateChecker($allRequest)
    {
        /*dd($allRequest);*/
        $terminal = Terminal::where('reg_no',$allRequest[0])->first();
        //dd($terminal,$allRequest);
        if ($terminal->shutter_sensor_status != $allRequest[2]) return [1,$terminal->id];
        elseif ($terminal->smoke_sensor_status != $allRequest[3]) return [1,$terminal->id];
        elseif ($terminal->gas_sensor_status != $allRequest[4]) return [1,$terminal->id];
        elseif ($terminal->motion_sensor_status != $allRequest[5]) return [1,$terminal->id];
        else return [0,$terminal->id];
    }

    public static function updateTerminalRelatedTable(array $attributes, array $updateFlag)
    {
        $term = Terminal::find($updateFlag[1]);
//        if ($term->is_terminal_on!=$attributes['Z']){
//            $term->is_terminal_on = $attributes['Z'];
//            $term->terminal_updated_at = now();
//
//            /*-------------------update terminal_actions table------------------------------*/
//            $terminalAction = TerminalAction::where('terminal_id',$attributes['A'])->first();
//            $terminalAction->status = $attributes['terminalstatus'];
//            $terminalAction->user_id = $attributes['userId'];
//            $terminalAction->update_time = Carbon::now()->format('Y-m-d h:i:s');
//            $terminalAction->save();
//        }
        //dd($term);
        if ($term->shutter_sensor_status!=$attributes[2]){
            $term->shutter_sensor_status = $attributes[2];
            $term->shutter_sensor_updated_at = now();
        }
        if ($term->smoke_sensor_status!=$attributes[3]){
            $term->smoke_sensor_status = $attributes[3];
            $term->smoke_sensor_updated_at = now();
        }
        if ($term->gas_sensor_status!=$attributes[4]){
            $term->gas_sensor_status = $attributes[4];
            $term->gas_sensor_updated_at = now();
        }
        if ($term->motion_sensor_status!=$attributes[5]){
            $term->motion_sensor_status = $attributes[5];
            $term->motion_sensor_updated_at = now();
        }

        $term->update();
    }

}
