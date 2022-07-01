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
use Illuminate\Support\Facades\DB;

class IndexController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
        $devices = Device::select('Dev_Name', 'DeviceID')->get();
        $objects = ObjectT::select('ObjectID', 'Obj_Name')->get();
        $locations = Location::select('LocationID', 'Location')->get();
        $units = Unit::select('id', 'describe_vi', 'describe_en', 'Dev_Unit')->get();
        return view('frontend.index', compact('devices', 'objects', 'locations', 'units'));
    }
    public function getAnalog(Request $request)
    {
        $timeStart = $request->timeStart;
        $timeEnd = $request->timeEnd;
        if (!$timeEnd) {
            $timeEnd = date('Y-m-d H:i:s');
        }
        $period = strtotime($timeEnd) - strtotime($timeStart);
        $data = [];
        if ($period < 0) {
            return response()->json($data);
        }
        $procedureName = GetProcedureTimeDialog($period);
        $execProcedureString = "EXEC ".$procedureName." @timeStart = '".$timeStart."',@timeEnd = '".$timeEnd."' ";
        if($request->device != ''){
            $execProcedureString.=',@DeviceID = '.$request->device.' ';
        }
        if($request->unit != ''){
            $execProcedureString.=',@unit = '.$request->unit.' ';
        }
        if($request->locationID!= ''){
            $execProcedureString.=',@locationID = '.$request->locationID.' ';
        }
        if($request->objectID != ''){
            $execProcedureString.=',@objectID = '.$request->objectID.' ';
        }
        $raw_data = DB::connection('beer')->select($execProcedureString);
        foreach ($raw_data as $key => $value) {
            $tempDeviceInfo = (array) $value;
            $device_name = $tempDeviceInfo['Dev_Name'];
            if (isset($data[$device_name])) {
                $time = $tempDeviceInfo['Recordtime'];
                $timeToSeconds = strtotime($time);
                array_push($data[$device_name]['values'], $tempDeviceInfo['Value']);
                array_push($data[$device_name]['times'], $time);
            } else {
                $device_id = $tempDeviceInfo['DeviceID'];
                $data[$device_name] = [
                    'values' => [$tempDeviceInfo['Value']],
                    'times' => [$tempDeviceInfo['Recordtime']],
                    'unit' => $tempDeviceInfo['Dev_Unit'],
                    'object' => $tempDeviceInfo['Obj_Name'],
                    'name' => $device_name,
                    'deviceId' => $device_id,
                    'description' => $tempDeviceInfo['Dev_Des'],
                    'timeInterval' => floor($period / 9) * 1000,
                    'ceil' => $tempDeviceInfo['t_ceil'],
                    'floor' => $tempDeviceInfo['t_floor'],
                ];
            }
        }
        return response()->json($data);
    }
}
function GetProcedureTimeDialog($period)
{
    if ($period < 3600) {
        return 'get_time_dialog_full';
    }
    if($period < 21600){
        return 'get_time_dialog_per_hour_full';
    }
    if ($period < 259200) {
        return 'get_time_dialog_per_day_full';
    }
    if ($period < 5184000) {
        return 'get_time_dialog_per_month_full';
    }
    return 'get_time_dialog_per_year_full';
}
