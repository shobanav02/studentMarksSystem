<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use Illuminate\Http\Request;
use Exception;

class SubjectController extends Controller
{
   
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
           $subject = Subject::create([
             'name' => isset($request->name) ? $request->name : null,
             'modules' => isset($request->modules) ? json_encode($request->modules) : null
           ]);

           $response = [
              'message' => 'Subjects are added successfully',
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
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  $idS
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
           
            $subject = Subject::where('id', $id)->update([
                'name' => isset($request->name) ? $request->name : null,
                'modules' => isset($request->modules) ? json_encode($request->modules) : null
              ]);
 
            $response = [
               'message' => 'Subjects are updated successfully',
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

   
}
