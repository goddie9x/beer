<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SeqType extends Model
{
    protected $connection = 'beer';
    protected $table = 'Seq_Type';
    protected $primaryKey = 'RecTypeID';
}
