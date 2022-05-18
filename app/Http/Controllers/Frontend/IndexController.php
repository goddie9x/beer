<?php
namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\TimeAnalog;
use App\Models\Device;
use App\Models\ObjectT;
use App\Models\Location;
use App\Models\Unit;
use Illuminate\Http\Request;

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
        $raw_data = (new TimeAnalog)->getAnalog($request->timeStart,$request->timeEnd,$request->device,$request->unit,$request->locationID);
        $data = [];
        foreach ($raw_data as $value) {
            $object_name = $value['Obj_Name'];
            if(isset($data[$object_name])){
                array_push($data[$object_name]['value'],$value['Value']);
                array_push($data[$object_name]['time'],$value['Recordtime']);
            }
            else{
                $data[$object_name] = [
                    'value' => [$value['Value']],
                    'time' => [$value['Recordtime']]
                ];
            }
        }
        return response()->json($data);
    }
}