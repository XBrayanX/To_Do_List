<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class todoList extends Model {
    use HasFactory;

    protected $table = 'todolist';

    protected $fillable = [
        'id',
        'name',
        'deadline',
        'complete',
        'created_at',
        'updated_at',
    ];  
}
