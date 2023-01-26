<?php

namespace App\Repositories;

use App\Models\Terminal;
use App\Models\TerminalAction;
use Carbon\Carbon;
use App\Repositories\Interfaces\terminalActionsRepositoryInterface;
use Illuminate\Support\Facades\DB;

class terminalActionsRepository implements terminalActionsRepositoryInterface
{

    public function index()
    {

    }

    public function dataTable()
    {

    }

    public function store(array $attributes)
    {
        TerminalAction::create($attributes);
    }

    public function show($id)
    {
        return TerminalAction::find($id);
    }

    public function update(array $attributes, array $updateFlag)
    {
        $term = Terminal::find($updateFlag[1]);
        if ($term->is_device_on!=$attributes['terminalstatus']){
            $term->is_device_on = $attributes['terminalstatus'];
            $term->device_status_update_time = Carbon::now()->format('Y-m-d h:i:s');

            /*-------------------update terminal_actions table------------------------------*/
            $terminalAction = TerminalAction::where('device_id',$attributes['terminalId'])->first();
            $terminalAction->status = $attributes['terminalstatus'];
            $terminalAction->user_id = $attributes['userId'];
            $terminalAction->update_time = Carbon::now()->format('Y-m-d h:i:s');
            $terminalAction->save();
        }
        if ($term->shutter_sensor_status!=$attributes['shuterSensorStatus']){
            $term->shutter_sensor_status = $attributes['shuterSensorStatus'];
            $term->shutter_sensor_updated_at = Carbon::now()->format('Y-m-d h:i:s');
        }
        if ($term->smoke_sensor_status!=$attributes['smokeSensorStatus']){
            $term->smoke_sensor_status = $attributes['smokeSensorStatus'];
            $term->smoke_sensor_updated_at = Carbon::now()->format('Y-m-d h:i:s');
        }
        if ($term->motion_sensor_status!=$attributes['motionSensorStatus']){
            $term->motion_sensor_status = $attributes['motionSensorStatus'];
            $term->motion_sensor_updated_at = Carbon::now()->format('Y-m-d h:i:s');
        }
        if ($term->gas_sensor_status!=$attributes['gasSensorStatus']){
            $term->gas_sensor_status = $attributes['gasSensorStatus'];
            $term->gas_sensor_updated_at = Carbon::now()->format('Y-m-d h:i:s');
        }
        $term->user_id = $attributes['userId'];
        $term->save();
    }

    public function delete($id)
    {
        // TODO: Implement delete() method.
    }
}
