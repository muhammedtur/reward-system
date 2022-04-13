<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

use App\Models\User;
use App\Helpers\UserHelper;

use Cache;
use Validator;

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
            $success['token'] =  $user->createToken('LaravelSanctumAuth')->plainTextToken;
            return $this->handleResponse($success, 'User created successfully!');
        }

        return response()->json(['status' => false, 'message' => 'Error occured while sign up!'], 400);
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required'
        ]);
   
        if ($validator->fails()) {
            return $this->handleError($validator->errors());
        }

        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $auth = Auth::user();

            $success['token'] =  $auth->createToken('LaravelSanctumAuth')->plainTextToken;

            return $this->handleResponse($success, 'User logged-in!');
        } else {
            return $this->handleError('Unauthorized');
        }
    }

    public function sendInvitation(Request $request)
    {
        $invitation['code'] = Str::random(32);
        $invitation['created_user'] = "28b00830-bb08-11ec-ac34-41364475c939";
        Cache::put("auth:invitationCode:{$invitation['code']}", $invitation);
        return $this->handleResponse($invitation, 'Invitation code generated successfully!');
    }
}
