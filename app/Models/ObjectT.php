<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ObjectT extends Model
{
    protected $connection = 'beer';
    protected $table = 'Object';
    protected $primaryKey = 'ObjectID';
    public function getObject()
    {
        return $this->hasOne(Location::class, 'Obj_LocID', 'LocationID');
    }
}
