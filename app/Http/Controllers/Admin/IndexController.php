<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Device;
use App\Models\Unit;
use Illuminate\Http\Request;

class IndexController extends Controller
{
    public function index()
    {
        return view('admin.index');
    }
    public function postAllUnit(){
       $units = Device::select('Dev_Unit')->distinct()->get()->toArray();
       Unit::insert($units);
    }
}