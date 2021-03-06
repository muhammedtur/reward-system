<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

use App\Models\User;
use App\Helpers\UserHelper;
use App\Mail\SendInvitationMail;

use Cache;
use Validator;
use Mail;

class AuthController extends BaseController
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required'
        ]);
   
        if ($validator->fails()) {
            return $this->handleError($validator->errors());
        }

        $user_check = User::where('email', $request->email)->first();

        if ($user_check) {
            // Return bad request if user register before with request email
            return $this->handleError('User already registered!');
        }

        $user = new User;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);

        if ($request->invitationCode) {
            UserHelper::checkInvitation($request->invitationCode, $user);
        }

        if ($user->save()) {
            $success['bearerToken'] =  $user->createToken('LaravelSanctumAuth')->plainTextToken;
            return $this->handleResponse($success, 'User created successfully!');
        }

        return $this->handleError('Error occured while sign up!');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $auth = Auth::user();

            $success['bearerToken'] =  $auth->createToken('LaravelSanctumAuth')->plainTextToken;

            return $this->handleResponse($success, 'User logged-in!');
        }

        return $this->handleError('The provided credentials do not match our records.');
    }

    public function sendInvitation(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
        ]);

        if ($validator->fails()) {
            return $this->handleError($validator->errors());
        }

        $invited_user = User::where('email', $request->email)->first();

        if ($invited_user) {
            return $this->handleError('Invited user already registered!');
        }

        $invitation['code'] = Str::random(32);
        $invitation['created_user'] = Auth::user()->guid;
        $invitation['email'] = base64_encode(env('APP_KEY').$request->email);

        // Cached invitation code for checking invited user
        Cache::put("auth:invitationCode:{$invitation['code']}", $invitation);
        // Cached invitation email for ignore multiple requests. Cache time is 10 minutes after that will be removed
        Cache::put("auth:invitationEmail:{$invitation['email']}", true, 600);

        // Send email to invited user with invitation code
        Mail::to($request->email)->queue(new SendInvitationMail(
            __('emails.invitation_title'),
            __('emails.invitation_msg', ['application_name' => env('APP_NAME')]),
            __('emails.invitation_code', ['invitation_code' => $invitation['code']])
        ));

        return $this->handleResponse('', 'Invitation code generated successfully!');
    }
}
