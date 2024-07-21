<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\ServiceProviderRequest;

class UpdateServiceProviderRequest extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request, ServiceProviderRequest $serviceProviderRequest)
    {
        if (auth()->user()->role_id !== 2) {
            return response()->json([
                'message' => 'Unauthorized Request'
            ], 401);
        }

        // Validate the query parameter
        $validatedInput = $request->validate([
            'address' => 'string|max:255',
            'lat' => 'string|max:255',
            'long' => 'string|max:255',
            'property_type' => 'integer',
            'price' => 'string|max:255',
            'date' => 'string|max:255',
            'time' => 'string|max:255',
            'description' => 'string',
            'additional_info' => 'string',
            'approved' => 'integer|in:0,1',
            'decline' => 'integer|in:0,1',
        ]);

        $serviceProviderRequest->update($validatedInput);

        return response()->json([
            'message' => "Updated successfully",
            'serviceProviderRequest' => $serviceProviderRequest
        ], 200);
    }
}
