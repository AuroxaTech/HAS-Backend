<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Carbon\Carbon;

class ApproveServiceProvide extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request, User $user)
    {   
        if (auth()->user()->id == $user->id) {
            return response()->json(['message' => 'Invalid action'], 400);
        }
        
        // Validate the query parameter
        $request->validate([
            'action' => 'required|in:approve,unapprove',
        ]);
        
        $action = $request->action;

        if ($action === 'approve') {
            $user->approved_at = Carbon::now();
            $message = 'User approved successfully';
        } elseif ($action === 'unapprove') {
            $user->approved_at = null;
            $message = 'User unapproved successfully';
        } else {
            return response()->json(['message' => 'Invalid action'], 400);
        }

        $user->save();

        return response()->json(['message' => $message], 200);
    }
}
