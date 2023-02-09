<?php

namespace App\Http\Controllers;

use App\Models\TeacherDetail;
use Illuminate\Http\Request;
use Exception;
class TeacherDetailController extends Controller
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
          
            return $request->all();
        } catch(Exception $e) {
            return response()->json([
                'error'   => true,
                'action' => 'error',
                'message' => $e->getMessage()
            ]);
        }
        
    }

    
}
