<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Device;
use App\Models\Threshold;
use App\Models\Unit;
use Illuminate\Http\Request;
use Encore\Admin\Layout\Content;

class IndexController extends Controller
{
    public function index(Content $content)
    {
        return $content
            ->title('Initial data')
            ->description($this->description['index'] ?? trans('admin.list'))
            ->view('admin.index');
    }
    public function initAllUnit(){
       Unit::truncate();
       $units = Device::select('Dev_Unit')->distinct()->get()->toArray();
       Unit::insert($units);
    }
    public function initThreshold(){
        Threshold::truncate();
        $devicesInfoForThreadhold = Device::select('DeviceID')->get()->toArray();
        Threshold::insert($devicesInfoForThreadhold);
    }
}