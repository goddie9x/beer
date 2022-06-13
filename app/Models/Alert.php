<?php
namespace App\Models;

use \Illuminate\Database\Eloquent\Model;

class Alert extends Model{
    protected $connection = 'beer';
    protected $table = 'alert';
    protected $primaryKey = 'id';
}