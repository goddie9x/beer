<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Device extends Model
{
    protected $connection = 'beer';
    protected $table = 'Device';
    protected $primaryKey = 'DeviceID';
}
