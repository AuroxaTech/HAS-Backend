<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    /**
     * Store a newly created payment in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validate the incoming request data
        $validatedData = $request->validate([
            'service_id' => 'nullable|exists:services,id',
            'service_provider_id' => 'nullable|exists:service_providers,id',
            'price' => 'required|string|max:255',
        ]);

        // Create a new payment record using the validated data
        $payment = Payment::create([
            'tenant_id' => auth()->id(),
            'service_id' => $validatedData['service_id'],
            'service_provider_id' => $validatedData['service_provider_id'],
            'price' => $validatedData['price'],
        ]);

        // Return a response, e.g., the newly created payment or a success message
        return response()->json(['message' => 'Payment successfully created!', 'payment' => $payment], 201);
    }
}
