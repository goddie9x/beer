<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Device;
use App\Models\Threshold;
use App\Models\Unit;
use Illuminate\Http\Request;

class IndexController extends Controller
{
    public function index()
    {
        return view('admin.index');
    }
    public function initAllUnit(){
       Unit::truncate();
       $units = Device::select('Dev_Unit')->distinct()->get()->toArray();
       Unit::insert($units);
    }
    public function initThreshold(){
        Threshold::truncate();
        $devicesInfoForThreadhold = Device::select('DeviceID','Dev_Unit')->get()->toArray();
        Threshold::insert($devicesInfoForThreadhold);
    }
}