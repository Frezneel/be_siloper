<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\PasswordChangeRequest;
use App\Http\Resources\UserResource;
use App\Models\User;

class AuthController extends Controller
{
    // Login #1
    public function login(LoginRequest $request)
    {
        $credentials = $request->validated();
        // Implement login logic here
        if (!Auth::attempt($credentials)) {
            return response()->json(
                ['message' => 'Invalid login email or password'],
                401);
        }

        /** @var User $user */
        $user = Auth::user();
        $user->load('roles'); // Load the roles relationship

        $permissions = $user->getActivePermissions();
        $deviceName = $request->header('User-Agent', 'auth-token');
        $token = $user->createToken($deviceName, $permissions)->plainTextToken;

        return response()->json([
            'message' => 'User Logged In successful',
            'user' => UserResource::make($user),
            'access_token' => $token,
            'token_type' => 'Bearer',
            'permissions' => $permissions,
        ]);
    }

    //Password Update #2
    public function updatePassword(PasswordChangeRequest $request)
    {
        $request->validated();
        /** @var User $user */
        $user = Auth::user();

        if(!$user->must_change_password){
            return response()->json([
                'message' => 'You cannot use this endpoint, Use profile settings instead'
            ],403);
        }

        if (password_verify($request->new_password, $user->password)) {
            return response()->json([
                'message' => 'Current password is same, make different',
            ], 400);
        }

        $user->password = bcrypt($request->new_password);
        $user->must_change_password = false;
        $user->save();

        return response()->json([
            'message' => 'Password updated successfully',
        ]);
    }

    //check self user /me #3

    public function me(Request $request){
        $user = $request->user();
        $user->load('roles');
        //Ambil data permission
        $permissions = $user->getActivePermissions();

        return response()->json([
            'user' => UserResource::make($user),
            'permission' => $permissions,
        ]);
    }

    //Logout #4
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'User logged out successfully',
        ]);
    }


}
