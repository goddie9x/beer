<?php
namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\TimeAnalog;
use App\Models\Device;
use App\Models\ObjectT;
use App\Models\Location;
use App\Models\Unit;
use Illuminate\Http\Request;
use Carbon\Carbon;

class IndexController extends Controller
{
    public function index()
    {
        $devices = Device::select('Dev_Name','DeviceID')->get();
        $objects = ObjectT::select('ObjectID','Obj_Name')->get();
        $locations = Location::select('LocationID','Location')->get();
        $units = Unit::select('id','describe_vi','describe_en','Dev_Unit')->get();
        return view('frontend.index', compact('devices', 'objects', 'locations', 'units'));
    }
    public function getAnalog(Request $request)
    {
        $timeStart = $request->timeStart;
        $timeEnd = $request->timeEnd;

        if($timeEnd == ''){
            $timeEnd = date('Y-m-d H:i:s');
        }
        $period = strtotime($timeEnd) - strtotime($timeStart);
        $raw_data = (new TimeAnalog)->getAnalog($timeStart,$timeEnd,$request->device,$request->unit,$request->locationID,$request->objectID);
        $data = [];

        foreach ($raw_data as $key=>$value) {
            $device_name = $value['Dev_Name'];
            if(isset($data[$device_name])){
                $time = $value['Recordtime'];
                /* $timeToSeconds = strtotime($time); 
                if(periodConditionHandle($period, $timeToSeconds)){ */
                    array_push($data[$device_name]['values'],$value['Value']);
                    array_push($data[$device_name]['times'],$time);
                //}
            }
            else{
                $data[$device_name] = [
                    'values' => [$value['Value']],
                    'times' => [$value['Recordtime']],
                    'unit' => $raw_data[$key]['Dev_Unit'],
                    'object' => $raw_data[$key]['Obj_Name'],
                    'name' => $device_name,
                    'description' => $raw_data[$key]['Dev_Des'],
                ];
            }
        }
        return response()->json($data);
    }
}
function periodConditionHandle($time,$period){
    $damage;
    if($period<3600){
        $damage = $time%60;
        return  $damage<=30;
    }
    else if($period<86400){
        $damage = $time%1800;
        return  $damage<=800;
    }
    else if($period<604800){
        $damage = $time%7200;
        return  $damage<=800;
    }
    else{
        $damage = $time%7200;
        return  $damage<=800;
    }
}