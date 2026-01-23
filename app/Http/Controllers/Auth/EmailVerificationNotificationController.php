<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class EmailVerificationNotificationController extends Controller
{
    /**
     * Send a new email verification notification.
     */
    public function store(Request $request): RedirectResponse
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->route('products.index');
        }

        try {
            $request->user()->sendEmailVerificationNotification();
        } catch (\Exception $e) {
            // Log error but don't break the flow
            Log::error('Failed to send verification email: ' . $e->getMessage());
        }

        return redirect()->route('verification.notice')->with('status', 'verification-link-sent');
    }
}
