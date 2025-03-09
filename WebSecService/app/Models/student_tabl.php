<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class student_tabl extends Model
{
   use HasFactory;
    protected $table = 'student';
    protected $fillable = ['name', 'email', 'phone', 'address'];
}
