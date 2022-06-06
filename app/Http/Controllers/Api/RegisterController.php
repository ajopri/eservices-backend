<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Api\BaseController as BaseController;
use App\Models\GroupUser;
use App\Models\User;
use App\Models\UserVerify;
use App\Notifications\RegisterNotification;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class RegisterController extends BaseController
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'contact_code' => 'required',
            'group_id' => 'required',
            'group_access' => 'required',
            'group_card_code' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $input = $request->all();

        // Input user
        $password = Str::random(12);
        $inputUser['password'] = bcrypt($password);
        $inputUser['name'] = $input['name'];
        $inputUser['email'] = $input['email'];
        $inputUser['contact_code'] = $input['contact_code'];
        $inputUser['role_id'] = $input['role_id'] ?? null;
        $inputUser['group_id'] = $input['group_id'];
        $inputUser['default'] = $input['default'];
        $user = User::create($inputUser);

        // Input user group
        $inputGrp['user_id'] = $user->id;
        $inputGrp['group_id'] = $user->group_id;
        $inputGrp['access'] = $input['group_access'];
        $inputGrp['card_code'] = $input['group_card_code'];
        $userGroup = GroupUser::create($inputGrp);

        //for verify
        $token = Str::random(64);
        UserVerify::create([
            'user_id' => $user->id,
            'token' => $token
        ]);
        $user['token'] = $token;

        $success['name'] =  $user->name;
        $success['email'] =  $user->email;
        $success['password'] =  $password;

        if ($user && $userGroup) {
            // $user->notify(new RegisterNotification($user));
            return $this->sendResponse($success, 'User register successfully.');
        } else {
            return $this->sendError('Registration failed.', ['error' => 'User register failed.'], Response::HTTP_BAD_REQUEST);
        }
    }
}
