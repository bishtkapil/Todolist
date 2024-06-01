<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    //
    protected $table ="tasks";
    protected $fillable = ['title', 'completed','created_at','updated_at'];
    public $timestamps = true;

}
