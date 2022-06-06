<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseController as BaseController;
use App\Models\User;
use App\Models\UserVerify;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class UserController extends BaseController
{
    public function index()
    {
        $id = Auth::id();
        $user = User::find($id)->load(['role', 'group']);

        if (is_null($user)) {
            return $this->sendError('User not found.');
        }

        return $this->sendResponse($user, 'User retrieved successfully.');
    }

    /**
     * Get list users
     */
    public function getCustomers()
    {
        $users = User::whereRoleId(env('ROLE_CUSTOMER'))->with('group')->get();

        if (is_null($users)) {
            return $this->sendError('User not found.');
        }

        return $this->sendResponse($users, 'User retrieved successfully.');
    }

    public function verifyAccount($token)
    {
        $verifyUser = UserVerify::where('token', $token)->first();
        $message = 'Sorry your email cannot be identified.';
        if (!is_null($verifyUser)) {
            $user = $verifyUser->user;
            if (!$user->is_email_verified) {
                $verifyUser->user->is_email_verified = 1;
                $verifyUser->user->email_verified_at = Carbon::now();
                $verifyUser->user->save();
                $message = "Your e-mail is verified. You can now login.";
            } else {
                $message = "Your e-mail is already verified. You can now login.";
            }
            return redirect()->away(env('FRONTEND_URL') . '/activate/' . $user->email);
        } else {
            return $this->sendError($message);
        }
    }
}
