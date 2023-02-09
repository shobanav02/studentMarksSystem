<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;
use Exception;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Student  $student
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        try {
            //get student selected subjects
            $studentData = Student::where('id',$request->student_id)
             ->first();

             if (empty($studentData)) {
                $response = [
                    'message' => 'Student details does not exist',
                    'action' => 'error',
                 ];
                return response()->json($response);
            }   
            
            $studentSelectedSubjects = json_decode($studentData['subject_id']);
            
            //if request contain subjects
            if (isset($request->subjects)) {
                // merge new selected subject and already selected subjects
                $newSelectedSubjects= array_unique(array_merge( $studentSelectedSubjects , $request->subjects));
                
                $updateFavoriteSubject = Student::where('id',$request->student_id)
                ->update([
                    'subject_id' => $newSelectedSubjects
                ]);
            }

            if (isset($request->favorite_subject_id)) {
               // check favorite subject_id from the request  exist in selected subject list
               // if exist update Favorite subject for the student
               if (in_array( $request->favorite_subject_id , $studentSelectedSubjects )) {
 
                    $updateFavoriteSubject = Student::where('id',$request->student_id)
                    ->update([
                        'favourite_subject' => $request->favorite_subject_id 
                    ]);

                } else {
                   $response = [
                      'message' => 'Favorite subject does not exist in the selected subject list',
                      'action' => 'error',
                    ];
                    return response()->json($response);
                }
            } 

            $response = [
                'message' => 'Student subject details updated successfully',
                'action' => 'success',
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
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Student  $student
     * @return \Illuminate\Http\Response
     */
    public function destroy(Student $student)
    {
        //
    }

    
}

