<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    use HasFactory;

    protected $table = 'teachers';

    protected $fillable = ['first_name', 'last_name' ,'email', 'date_of_birth' , 'qualification' ,'class' ,'teaching_subject_id' ,'user_id'];
}
