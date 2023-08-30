<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Requests\UpdateRoleRequest;

class UserController extends Controller
{
    public function getUser(){
        $user = auth()->user();
        return response()->json($user);
    }

    public function updateUser(UpdateUserRequest $request){
        $user = auth()->user();
        $user->update($request->all());
        return response()->json(['message' => 'User successfully updated.', 'user' => $user], 200);
    }

    public function users(){
        $users= User::all();
        return response()->json(['users' => $users]);
    }

    public function destroy($id){
        $user = User::find($id);
        if(!$user){
            return response()->json(['message' => 'User not found.'], 404);
        }
        $user->delete();
        return response()->json(['message' => 'User successfully deleted.'], 200);
    }

    public function banUser($id){
        $user = User::find($id);
        if(!$user){
            return response()->json(['message' => 'User not found.'], 404);
        }
        if($user->role == '1'){
            return response()->json(['message' => 'Admin cannot be banned.'], 403);
        }
        if($user->status == '1'){
            $user->status = '0';
            $user->save();
            return response()->json(['message' => 'User successfully unbanned'], 200);
        }
        $user->status = '1';
        $user->save();
        return response()->json(['message' => 'User successfully banned'], 200);
    }

    public function updateRole(UpdateRoleRequest $request){
        $user = User::find($request->user_id);
        $count = User::where('role', '1')->count();
        if($count == '1' && $user->role == '1'){
            return response()->json(['message' => 'Last admin cannot be changed.'], 403);
        }
        $user->role = $request->role_id;
        $user->save();
        return response()->json(['message' => 'User role successfully changed.'], 200);
    }
}
