<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\ErrorReport;
use Illuminate\Http\Request;
use Auth;
use Log;
use Validator;
class ErrorReportController extends Controller
{
    public function store(Request $request)
    {
        //--- Validation Section
        $rules = [
                'error_message' => 'required|string',
                'page_url' => 'required|url',
                ];
        $validator = Validator::make($request->all(), $rules);       
        if ($validator->fails()) {
          return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
        }

        // Check if the user is authenticated
        $userId = Auth::check() ? Auth::id() : null;
        // Check if an error report for the current page URL already exists
        $existingReport = ErrorReport::where('page_url', $request->input('page_url'))
                                     ->where('user_id', $userId)
                                     ->first();

        if ($existingReport) {
            return response()->json(array('errors' => [ 0 =>  'An error report for this page has already been submitted.']));
        }
        $errorReport = new ErrorReport([
            'error_message' => $request->input('error_message'),
            'page_url' => $request->input('page_url'),
            'user_id' => $userId,
        ]);       
        $errorReport->save(); 
        
         return response()->json([
            'success' => true,
            'message' => 'Thank you for your feedback!',
        ]);     

    }
}
