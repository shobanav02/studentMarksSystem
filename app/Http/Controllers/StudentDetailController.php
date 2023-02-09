<?php

namespace App\Http\Controllers;

use App\Models\StudentDetail;
use Illuminate\Http\Request;
use Exception;
use App\Models\Student;
use App\Models\Teacher;

/**
 * This controller will handle function related to student subject details, marks and rank
 */

class StudentDetailController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        try {
          $studentSubjectDetails = StudentDetail::with(['students','subjects'])->where('student_id' , $id)->get();

          $response = [
            'message' => 'Student subject details loaded successfully',
            'action' => 'success',
            'data' => $studentSubjectDetails
         ];
         return response()->json($response);

        } catch(Exception $e) {
            return response()->json([
                'error'   => true,
                'action' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }


    /**
     * following function is to view student details
     *  belongs to the teacher who is handling the class
     */
    public function getStudentDetails($id) {
        try {
           
            $teacherData = Teacher::where('user_id',$id)->first();
            
            if (!empty( $teacherData)) {
               $studentDetails = Student::with('studentDetails')
                  ->where('class',$teacherData['class'])
                  ->get();

                $response = [
                  'message' => 'Student details loaded successfully',
                  'action' => 'success',
                  'data' => $studentDetails
               ];
               return response()->json($response);
            }
            
            $response = [
                'message' => 'User does not exist',
                'action' => 'error',
             ];
            return response()->json($response);
           
        } catch(Exception $e) {
            return response()->json([
                'error'   => true,
                'action' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
     * following function will update marks for the students 
     */

    public function updateStudentDetails(Request $request) {
        try {
            
            //get the class data by passing teacher user id
            $teacherData = Teacher::where('user_id',$request->teacher_id)->first();
            
            if (!empty($teacherData)) {
               
                $studentData = Student::where('class',$teacherData['class'])
                  ->where('id',$request->student_id)
                  ->first();
                
                if (empty($studentData)) {
                    $response = [
                        'message' => 'Student details does not exist for the given teacherId',
                        'action' => 'error',
                     ];
                    return response()->json($response);
                }   
                
                $studentSelectedSubjects = json_decode($studentData['subject_id']);
                
                //check if the teacher has access to update the marks for the students
                // if the teacher subject_id matches with the student selected subject id ,
                //then teacher can update marks for the students

                if (in_array( $teacherData['teaching_subject_id'] , $studentSelectedSubjects )) {
                
                   $rank = '';
                   if ($request->marks > 90) {
                     $rank = '1';
                   } elseif ($request->marks > 80 ) {
                      $rank ='2';
                    } elseif ($request->marks > 70 ) {
                      $rank ='3';
                    } elseif ($request->marks > 60 ) {
                      $rank ='4';
                    } elseif ($request->marks > 50 ) {
                       $rank ='5';
                    } elseif ($request->marks > 40 ) {
                       $rank ='6';
                    } else {
                      $rank = '7';
                    }

                   $checkDataExits =  StudentDetail::where('student_id' ,$request->student_id)
                       ->where('subject_id',$request->subject_id)
                       ->first();
                       
                    if (!empty($checkDataExits)) {   
                       $updateStudentSubjectData = StudentDetail::where('student_id' ,$request->student_id)
                           ->where('subject_id',$request->subject_id)
                           ->update([
                              'marks' => isset($request->marks) ? $request->marks : null,
                              'rank' => $rank
                           ]);
                    } else {
                        $updateStudentSubjectData = StudentDetail::create([
                              'subject_id' => $request->subject_id,
                              'student_id' => $request->student_id,
                              'marks' => isset($request->marks) ? $request->marks : null,
                              'rank' => $rank
                        ]);
                        
                    }
                   
                    $response = [
                        'message' => 'Student marks updated successfully',
                        'action' => 'success',
                    ];
                    return response()->json($response);   
                } else {
                    $response = [
                        'message' => 'Teacher does not have access to update marks for the students',
                        'action' => 'error',
                    ];
                    return response()->json($response);  
                }
            }
            
        } catch(Exception $e) {
            return response()->json([
                'error'   => true,
                'action' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    } 
}
