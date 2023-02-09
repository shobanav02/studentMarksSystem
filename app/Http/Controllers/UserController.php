<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Exception;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\Subject;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use App\Models\TeacherDetail;
use App\Models\StudentDetail;


class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $subjects = Subject::select('name','modules')->get();

        $formattedOutput = $subjects->map(function ($item) {
            $item->modules = json_decode($item->modules);
            return $item;
        });
        
        $response = [
            'message' => 'Home Page loaded successfully',
            'action' => 'success',
            'data' =>  $formattedOutput
         ];
         return response()->json($response);
        
    }
    
    /**
     * Login function 
     * check given password from request and user password are same .
     *  If it is equal , the user will be allow to login to the system
     * 
    */

    public function login(Request $request) {
        try {

            $userData = User::where('email' ,$request->email)->where('blocked', false)->first();

            if (!empty($userData)) {
                
                // to check the given paasword and user password are equal 
                $isCorrectPassword = Hash::check($request->password, $userData['password']);

            if ($isCorrectPassword ) {
                
                $subjectDetails = [] ;
                if ($userData['user_role_id'] == 1) {
                   
                    $subjectDetails = Subject::with('teachers')->get();

                } else if ( $userData['user_role_id'] == 2) {
                    $teacherData = Teacher::where('user_id' , $userData['id'])->first();

                    $subjectDetails = Subject::with(['teachers' => function($q) use($teacherData) {
                        $q->where('teacher_id',$teacherData['id']);
                    }])->get();
 
                } else{

                    $studentData = Student::where('user_id' , $userData['id'])->first();

                    $subjectDetails = Subject::with(['students' => function($q) use($studentData) {
                        $q->where('student_id',$studentData['id']);
                    }])->get();
                 
                }

                $response = [
                    'message' => 'Logged in successfully',
                    'action' => 'success',
                    'data' => $subjectDetails
                 ];
                 return response()->json($response);
            } else {

                $response = [
                    'message' => 'Invalid Credentials',
                    'action' => 'error',
                 ];
                 return response()->json($response);
            }
        } 
        $response = [
            'message' => 'User does not exist',
            'action' => 'error',
         ];
         return response()->json($response);

        } catch( Exception $e) {
            return response()->json([
                'error'   => true,
                'action' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {

            $user = User::create([
                "name" => isset($request->first_name) ? $request->first_name : null,
                "email" => isset($request->email) ? $request->email : null,
                "password" => isset($request->password) ? Hash::make($request->password) : null ,
                "user_role_id" => isset($request->role) ? $request->role : null
            ]);
            
            if (isset($request->role)) {
              if ($request->role == 2) {
                $teacher = Teacher::create([
                    "first_name" => isset($request->first_name) ? $request->first_name : null,
                    "last_name" => isset($request->last_name) ? $request->last_name : null,
                    "email" => isset($request->email) ? $request->email : null,
                    "date_of_birth" => isset($request->date_of_birth) ? Carbon::parse($request->date_of_birth)->format('Y-m-d') : null,
                    "qualification" => isset($request->qualification) ? $request->qualification : null,
                    "class" => isset($request->class) ? $request->class : null,
                    "teaching_subject_id" => isset($request->teaching_subject) ? $request->teaching_subject : null,
                    "user_id" => $user['id']
                ]);
              } else {
                $teacher = Student::create([
                    "first_name" => isset($request->first_name) ? $request->first_name : null,
                    "last_name" => isset($request->last_name) ? $request->last_name : null,
                    "email" => isset($request->email) ? $request->email : null,
                    "class" => isset($request->class) ? $request->class : null,
                    "subject_id" => isset($request->subjects) ? json_encode($request->subjects) : null,
                    "favorite_subject" => isset($request->favorite_subject) ? json_encode($request->favorite_subject) : null,
                    "user_id" => $user['id']
                ]);
              }
            }

            $response = [
               'message' => 'Users are added successfully',
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
     * To block the user account.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            $userData = User::where('id' ,$id)->first();

            if (!empty($userData)) {
               
                $user = User::where('id',$id)->update([
                    'blocked' => isset($request->blocked) && ($request->blockedd === true) ? 1 : 0,
                 
                ]);

                $response = [
                    'message' => 'User has been blocked successfully',
                    'action' => 'success',
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

    
}
