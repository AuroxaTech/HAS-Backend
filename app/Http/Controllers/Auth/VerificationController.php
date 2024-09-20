<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\VerifiesEmails;
use Illuminate\Http\Request;
use Illuminate\Auth\Events\Verified;
use App\Models\User;

class VerificationController extends Controller
{
    use VerifiesEmails;

    protected $redirectTo = RouteServiceProvider::HOME;

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('signed')->only('verify');
        $this->middleware('throttle:6,1')->only('verify', 'resend');
    }

    public function verify(Request $request, $id, $hash)
    {
        // Find the user by ID
        $user = User::findOrFail($id);

        // Verify the email hash
        if (!hash_equals((string) $hash, sha1($user->getEmailForVerification()))) {
            // Return view for failure
            return view('emails.email_verification_failure', [
                'message' => 'Invalid verification link.'
            ]);
        }

        // If already verified
        if ($user->hasVerifiedEmail()) {
            // Return view for already verified
            return view('emails.email_verification_success', [
                'message' => 'Your email is already verified.'
            ]);
        }

        // Mark the email as verified
        if ($user->markEmailAsVerified()) {
            event(new Verified($user));

            // Optionally update your custom _is_verified field
            $user->_is_verified = 1;
            $user->save();

            // Return view for success
            return view('emails.email_verification_success', [
                'message' => 'Your email has been verified successfully!'
            ]);
        }

        // In case something goes wrong, return a failure view
        return view('emails.email_verification_failure', [
            'message' => 'Email verification failed. Please try again.'
        ]);
    }
}
