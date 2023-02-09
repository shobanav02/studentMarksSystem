<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;
    protected $table = 'students';

    protected $fillable = ['first_name', 'last_name' ,'email', 'class' ,'subject_id' ,'favourite_subject','user_id'];

    
    public function studentDetails()
    {
          return $this->hasOne('App\Models\StudentDetail', 'id', 'student_id');
    }

    
}
