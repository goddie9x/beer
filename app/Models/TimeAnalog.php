<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TimeAnalog extends Model
{
    protected $connection = 'beer';
    protected $table = 'Time_Analog';
    protected $primaryKey = 'STT';

    public function getAnalog($timeStart,$timeEnd='',$device='',$unit='', $locationID='',$objectID=''){
        $where = [];
        if($device != ''){
            array_push($where, ['Device.DeviceID', '=', $device]);
        }
        if($unit != ''){
            array_push($where, ['Device.Dev_Unit', '=', $unit]);
        }
        if($locationID != ''){
            array_push($where, ['Object.Obj_LocID', '=', $locationID]);
        }
        if($objectID != ''){
            array_push($where, ['Object.ObjectID', '=', $objectID]);
        }
        return $this->join('Device', 'Time_Analog.DeviceID', '=', 'Device.DeviceID')
        ->join('Object', 'Device.Dev_ObjID', '=', 'Object.ObjectID')
        ->join('Location', 'Object.Obj_LocID', '=', 'Location.LocationID')
        ->select('Device.Dev_Name','Device.Dev_Des','Object.Obj_Name','Time_Analog.Value', 'Time_Analog.Recordtime','Device.Dev_Unit')
        ->where($where)
        ->whereBetween('Time_Analog.Recordtime', [$timeStart, $timeEnd])
        ->get();
    }
    public function getAnalogWithPeriord($timeStart,$timeEnd='',$device='',$unit='', $locationID='',$objectID=''){
        $period = strtotime($timeEnd) - strtotime($timeStart);
        $where = [['Time_Analog.Recordtime', '>=', $timeStart],['Time_Analog.Recordtime', '<=', $timeEnd]];
        if($device != ''){
            array_push($where, ['Device.DeviceID', '=', $device]);
        }
        if($unit != ''){
            array_push($where, ['Device.Dev_Unit', '=', $unit]);
        }
        if($locationID != ''){
            array_push($where, ['Object.Obj_LocID', '=', $locationID]);
        }
        if($objectID != ''){
            array_push($where, ['Object.ObjectID', '=', $objectID]);
        }
        return $this->join('Device', 'Time_Analog.DeviceID', '=', 'Device.DeviceID')
        ->join('Object', 'Device.Dev_ObjID', '=', 'Object.ObjectID')
        ->join('Location', 'Object.Obj_LocID', '=', 'Location.LocationID')
        ->select('Device.Dev_Name','Device.Dev_Des','Object.Obj_Name','Time_Analog.Value', 'Time_Analog.Recordtime','Device.Dev_Unit')
        ->where($where)->get();
    }
}
