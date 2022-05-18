<?php
namespace App\Models;

use \Illuminate\Database\Eloquent\Model;

class BeerType extends Model{
    protected $connection = 'beer';
    protected $table = 'Beer_Type';
    protected $primaryKey = 'TypeID';
}