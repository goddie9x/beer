<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    protected $connection = 'beer';
    protected $table = 'Location';
    protected $primaryKey = 'LocationID';
}
