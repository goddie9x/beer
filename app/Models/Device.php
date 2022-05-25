<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Device extends Model
{
    protected $connection = 'beer';
    protected $table = 'Device';
    protected $primaryKey = 'DeviceID';
    public function GetAllDevices($DeviceID="",$objectID="",$unit="",$locationID=""){
        $where = [];
        if($DeviceID != ""){
            array_push($where, ['DeviceID', '=', $DeviceID]);
        }
        if($objectID != ""){
            array_push($where, ['Dev_ObjID', '=', $objectID]);
        }
        if($unit != ""){
            array_push($where, ['Dev_Unit', '=', $unit]);
        }
        if($locationID != ""){
            array_push($where, ['Dev_LocID', '=', $locationID]);
        }
        return $this->join('Object', 'Device.Dev_ObjID', '=', 'Object.ObjectID')
        ->join('Location', 'Object.Obj_LocID', '=', 'Location.LocationID')
        ->select('Device.DeviceID','Device.Dev_Name','Device.Dev_Des','Object.Obj_Name','Location.Location','Device.Dev_Unit')
        ->where($where)->get();
    }
}
