<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Terminal extends Model
{
    use HasFactory;
    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    protected $fillable=['reg_no','terminal_id','user_id','shutter_sensor_status','shutter_sensor_updated_at',
        'smoke_sensor_status','smoke_sensor_updated_at','gas_sensor_status','gas_sensor_updated_at',
        'motion_sensor_status','motion_sensor_updated_at','is_terminal_on','terminal_updated_at'];
}
