<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\Terminal\TerminalRegisterRequest;
use App\Models\Terminal;
use App\Models\TerminalAction;
use App\Models\TerminalDataReceive;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TerminalController extends Controller
{
    public function index()
    {
        try {
            $user_terminal_list= TerminalAction::where('user_id',Auth::user()->id)->get();
            return response()->json([
                'status' => true,
                'message' => 'success',
                'data' => $user_terminal_list
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => /*'something went wrong'*/$th->getMessage()
            ], 500);
        }
    }
    public function show($id)
    {
        try {
            $terminal= TerminalAction::find($id)->first();
            return response()->json([
                'status' => true,
                'data' => $terminal
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => 'something went wrong'/*$th->getMessage()*/
            ], 500);
        }
    }

    public function terminalRegister(TerminalRegisterRequest $request)
    {
        try {
            DB::beginTransaction();
            $terminal_info=TerminalAction::where([['reg_no',$request->reg_no],['imei',$request->imei]])->first();
            //dd($terminal_info,$request->all());
            if (!$terminal_info || $terminal_info->user_id!=null){
                return response()->json([
                    'status' => false,
                    'message' => 'Terminal was not found or already taken!'
                ], 404);
            }
            $terminal_info->allocate_place = $request->allocate_place;
            $terminal_info->status = 1;
            $terminal_info->status_updated_at = now();
            $terminal_info->is_approved = 1;
            $terminal_info->approved_at = now();
            $terminal_info->user_id = Auth::user()->id;
            $terminal_info->update();

            Terminal::create([
                'terminal_id' => $terminal_info->id,
                'reg_no' => $terminal_info->reg_no,
                'user_id' => Auth::user()->id,
                'shutter_sensor_status' => 0,
                'shutter_sensor_updated_at' => now(),
                'smoke_sensor_status' => 0,
                'smoke_sensor_updated_at' => now(),
                'gas_sensor_status' => 0,
                'gas_sensor_updated_at' => now(),
                'motion_sensor_status' => 0,
                'motion_sensor_updated_at' => now(),
                'is_terminal_on' => 1,
                'terminal_updated_at' => now(),
            ]);

            DB::commit();
            return response()->json([
                'status' => true,
                'message' => 'Successfully Added!'
            ], 200);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => /*'something went wrong'*/$th->getMessage()
            ], 500);
        }

    }

    public function terminalActivities(Request $request)
    {
        try {
            $terminal_info=TerminalAction::find($request->id);
            $offset=$request->offset ? $request->offset : 15;
            $terminal_activities=TerminalDataReceive::Where('reg_no',$terminal_info->reg_no)->latest()->paginate($offset);
            return response()->json([
                'status' => true,
                'data' => $terminal_activities
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => /*'something went wrong'*/$th->getMessage()
            ], 500);
        }
    }
}
