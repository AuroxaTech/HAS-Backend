<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class DeleteUserController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {   
        $user = auth()->user();
        
        // Delete all properties associated with the user
        $user->properties()->delete();
        
        // Logout the user
        $user->currentAccessToken()->delete();
        
        // Delete the user
        $user->delete();

        // Return a success response
        return response()->json([
            'message' => 'User, all associated properties deleted, and logged out successfully'
        ], 200);
    }
}
