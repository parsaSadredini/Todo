<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    public static $validationRules = [
        'title' => 'required',
        'description' => 'required',
        'when_to_do' => 'required',
    ];
    public function user(){
        return $this->hasOne('App\\Models\\User','id','id_user');

    }

    protected $table = "tasks";
}
