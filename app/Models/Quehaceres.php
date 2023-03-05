<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quehaceres extends Model {
    use HasFactory;

    //create_at y update_at están automatizados no necesitamos que el modelo los utilize
    public $timestamps = false;
}
