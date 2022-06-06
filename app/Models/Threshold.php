<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Threshold extends Model
{
    protected $connection = 'beer';
    protected $table = 'Threshold';
    protected $primaryKey = 'id';
    public $timestamps = false;
    public function getAllThresHoldInfo()
    {
        return $this->join('Device', 'Device.DeviceID', '=', 'Threshold.DeviceID')
            ->select('Threshold.*', 'Device.Dev_Name', 'Device.Dev_Unit')
            ->get();
    }
}
