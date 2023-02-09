<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

//teacher subject details
class TeacherDetail extends Model
{
    use HasFactory;

    protected $table = 'teacher_details';

    protected $fillable = ['subject_id','teacher_id' , 'assignments'];

   

    public function subjects()
    {
        return $this->belongsTo('App\Models\Subject', 'subject_id', 'id');
    }
}
