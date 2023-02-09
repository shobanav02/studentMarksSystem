<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    use HasFactory;

    protected $table = 'subjects';

    protected $fillable = ['name','modules'];


    public function teachers()
    {
          return $this->hasOne('App\Models\TeacherDetail', 'id', 'subject_id');
    }

    public function students()
    {
          return $this->hasOne('App\Models\StudentDetail', 'id', 'subject_id');
    }
}
