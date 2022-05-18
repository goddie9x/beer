<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Device;

class TimeDigital extends Model
{
    protected $connection = 'beer';
    protected $table = 'Time_Digital';
    protected $primaryKey = 'STT';
}
