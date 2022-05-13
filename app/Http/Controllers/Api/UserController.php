<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseController as BaseController;
use App\Models\User;
use App\Models\UserVerify;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class UserController extends BaseController
{
    public function index()
    {
        $id = Auth::id();
        $user = User::find($id)->load('role');

        if (is_null($user)) {
            return $this->sendError('User not found.');
        }

        return $this->sendResponse($user, 'User retrieved successfully.');
    }

    public function verifyAccount($token)
    {
        $verifyUser = UserVerify::where('token', $token)->first();

        if (!is_null($verifyUser)) {
            $user = $verifyUser->user;
            if (!$user->is_email_verified) {
                $verifyUser->user->is_email_verified = 1;
                $verifyUser->user->email_verified_at = Carbon::now();
                $verifyUser->user->save();
                $message = "Your e-mail is verified.";

                $activateToken = Str::random(64);
                UserVerify::create([
                    'user_id' => $user->id,
                    'token' => $activateToken
                ]);
                return redirect()->away(env('FRONTEND_URL') . '/activate/' . $activateToken . '?email=' . $user->email);
            } else {
                return redirect()->away(env('FRONTEND_URL') . '/activate' . '?email=' . $user->email);
            }
        } else {
            return redirect()->away(env('FRONTEND_URL') . '/login');
        }
    }

    public function activateAccount(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => ['required', 'email'],
            'password' => ['required', 'confirmed'],
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        if (!is_null($request->token)) {
            $verifyUser = UserVerify::where('token', $request->token)->first();
            $user = $verifyUser->user;
        } else {
            $verifyUser = User::with('verify')->where('email', $request->email)->first();
            $user = $verifyUser;
        }

        if (is_null($verifyUser)) {
            return $this->sendError('User not found.');
        }

        if (!$user->active) {
            $user->active = 1;
            $user->activated_at = Carbon::now();
            $user->password = Hash::make($request->password);
            $user->remember_token = Str::random(60);
            $user->save();
            $message = "Your e-mail is verified. You can login now.";
            UserVerify::where('user_id', $user->id)->delete();
        } else {
            $message = "Your e-mail is already active. You can login now.";
        }

        return $this->sendResponse($message, 'User retrieved successfully.');
    }
}
