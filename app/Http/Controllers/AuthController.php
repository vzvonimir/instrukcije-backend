<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\RegisterUserRequest;
use App\Http\Requests\LoginUserRequest;
use App\Http\Requests\ChangePasswordRequest;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\ResetMailRequest;
use App\Models\ValidationKey;
use App\Http\Requests\ResetPasswordRequest;

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

    public function requestValidationKey(ResetMailRequest $request){
        $user = User::where('email', $request->email)->first();
        if($user->status != '0'){
            return response()->json(['message' => 'Your account is banned.'], 401);
        }
        $user->generateCode($user);
        return response()->json(['message' => 'Reset code has been sent to your email address!'], 200);
    }

    public function resetPassword(ResetPasswordRequest $request){
        $user = User::where('email', $request->email)->first();
        $user_key = ValidationKey::where('user_id', $user->id)->first();
        if($request->key !== $user_key->key){
            return response()->json(['error' => 'Invalid validation key.'], 400);
        }
        if($user_key->isExpire()){
            return response()->json(['error' => 'Validation key has expired.'], 401);
        }
        User::whereId($user->id)->update([
            'password' => bcrypt($request->password)
        ]);

        return response()->json(['message' => 'New password set successfully!'], 200);
    }

}
