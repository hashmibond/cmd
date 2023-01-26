<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\Terminal\UpdateTerminalActionRequest;
use App\Models\Terminal;
use App\Models\TerminalAction;
use App\Repositories\Interfaces\terminalActionsRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Toastr;

class TerminalActionController extends Controller
{

    private $terminalRepository;
    public function __construct(terminalActionsRepositoryInterface $terminalRepository)
    {
        $this->terminalRepository= $terminalRepository;
    }

    public function index()
    {

    }


    public function create()
    {

    }

    public function store(Request $request)
    {

    }

    public function show($id)
    {

    }


    public function edit($id)
    {

    }


    public function update(UpdateTerminalActionRequest $request, TerminalAction $terminalAction)
    {
        try {
            $updateFlag=self::updateChecker($request->all());

            if ($updateFlag[0]==1){
                /*----------update terminals and terminal_actions table-----------*/
                DB::beginTransaction();
                $this->terminalRepository->update($request->all(),$updateFlag);
                DB::commit();
            }
            else{
                return response()->json([
                    'status' => false,
                    'message' => 'Nothing to update'
                ], 422);
            }

            return response()->json([
                'status' => true,
                'message' => 'Terminal Updated Successfully'
            ], 200);

        } catch (\Throwable $th) {
            DB::rollback();
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }


    public function destroy($id)
    {
        $this->terminalRepository->delete($id);

        return redirect()->route('products.index')
            ->with('success','Product deleted successfully');
    }

    public static function updateChecker($allRequest)
    {
        /*dd($allRequest);*/
        $terminal = Terminal::where('device_id',$allRequest['terminalId'])->first();
        if ($terminal->is_device_on != $allRequest['terminalstatus']) return [1,$terminal->id];
        elseif ($terminal->shutter_sensor_status != $allRequest['shuterSensorStatus']) return [1,$terminal->id];
        elseif ($terminal->smoke_sensor_status != $allRequest['smokeSensorStatus']) return [1,$terminal->id];
        elseif ($terminal->motion_sensor_status != $allRequest['motionSensorStatus']) return [1,$terminal->id];
        elseif ($terminal->gas_sensor_status != $allRequest['gasSensorStatus']) return [1,$terminal->id];
        else return [0,$terminal->id];
    }
}
