<?php

namespace App\Http\Middleware;

use Cache;
use Closure;
use Illuminate\Http\Request;

class SendInvitation
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if ($request->email) {
            $email_crypted = base64_encode(env('APP_KEY').$request->email);

            if (Cache::get("auth:invitationEmail:{$email_crypted}")) {
                return response()->json([
                    'status' => false,
                    'message' => 'You already invited your friend with this email! Please wait about 10 minutes and try again!'
                ], 400);
            }

            return $next($request, $email_crypted);
        }
        // Return bad request message
        return response()->json(['status' => false, 'message' => 'The email field is required!'], 400);
    }
}
