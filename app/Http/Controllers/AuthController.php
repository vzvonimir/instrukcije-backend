<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\RegisterUserRequest;
use App\Http\Requests\LoginUserRequest;
use App\Http\Requests\ChangePasswordRequest;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function register(RegisterUserRequest $request){
        User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'role' => $request->role,
            'city' => $request->city,
            'password' => bcrypt($request->password)
        ]);
        return response()->json(['message' => 'Registration successful'], 200);
    }

    public function login(LoginUserRequest $request){
        $credentials = $request->only('email', 'password');

        if (!Auth::attempt($credentials)) {
            return response()->json(['message' => 'There was an error with your email or password. Please try again.'], 401);
        }

        $user = Auth::user();
        $token = $user->createToken('token')->plainTextToken;
        $authorization = ['token' => $token, 'type' => 'bearer'];

        return response()->json(['user' => $user, 'authorization' => $authorization], 200);
    }

    public function changePassword(ChangePasswordRequest $request){

        if(!Hash::check($request->old_password, auth()->user()->password)){
            return response()->json(['message' => 'Wrong the old password.'], 403);
        }

        if($request->old_password === $request->new_password){
            return response()->json(['message' => 'New password must be different from than last one.'], 400);
        }

        User::whereId(auth()->user()->id)->update([
            'password' => bcrypt($request->new_password)
        ]);

        return response()->json(['message' => 'New password set successfully!'], 200);

    }

    public function logout(Request $request){
        auth()->user()->tokens()->delete();
        return response()->json(['message' => 'Successfully logged out.'], 200);
    }

}
