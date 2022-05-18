<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CIPType extends Model
{
    protected $connection = 'beer';
    protected $table = 'CIP_Type';
    protected $primaryKey = 'TypeID';
}
