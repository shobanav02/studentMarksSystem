<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentDetail extends Model
{
    use HasFactory;

    protected $table = 'student_details';

    protected $fillable = ['subject_id','student_id' , 'assignments' ,'marks' , 'rank'];

    public function subjects()
    {
        return $this->belongsTo('App\Models\Subject', 'subject_id', 'id');
    }

    //
    public function students()
    {
        return $this->belongsTo('App\Models\Student', 'student_id', 'id');
    }
}
