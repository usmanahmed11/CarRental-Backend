<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;
use App\Mail\UserActivationEmail;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;


class NewUserController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => ['required', 'string', 'min:8'],
            'role_id' => 'required|exists:roles,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        $role = Role::find($request->role_id);

        // validate that the role exists
        if (!$role) {
            return response()->json(['error' => 'Invalid role_id'], 422);
        }

        // validate and create new user
        $user = new User([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $user->role_id = $request->role_id;
        $user->save();

        $token = $user->createToken('authToken')->accessToken;
        $user->remember_token = $token;
        $user->save();
        
        // send activation email
        Mail::to($user->email)->send(new UserActivationEmail($token));
        return response()->json(['message' => 'Account Created Successfully Please Verify The Mail For Login'], 200);
    }


    public function index()
    {
        $roles = Role::all();
        return response()->json($roles);
    }
    public function getEmail($id)
    {
        $user = User::findOrFail($id);
        $role = Role::findOrFail($user->role_id);

        return response()->json([
            'email' => $user->email,
            'name' => $user->name,
            'role_id' => $user->role_id,
            'role_name' => $role->role_name
        ]);
    }


    public function activateAccount(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'password_confirmation' => ['required', 'same:password'],
        ]);

        if ($validator->fails()) {
            return response()->json(['error' =>  $validator->errors()], 400);
        }

        // Find the user using the activation token
        $user = User::where('remember_token', $request->token)->first();

        // If the user is not found, return an error message
        if (!$user) {
            return response()->json(['error' => 'Invalid activation link.'], 400);
        }

        // Update the user's password and set the activation token to null

        $user->password = Hash::make($request->password);
        $user->remember_token = null;
        $user->email_verified_at = Carbon::now();
        $user->save();

        return response()->json(['message' => 'Account activated successfully. Now You Can Login With Your New Password'], 200);
    }


    public function showUsers(Request $request)
    {
        $perPage = 10; // number of users to send per page
        $page = $request->query('page') ?: 1; // current page number
        $users = User::latest()->skip(($page - 1) * $perPage)->take($perPage)->get();
        $totalUsers = User::count();

        $userData = [];
        foreach ($users as $user) {
            $userData[] = [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'status'=>$user->status,
                'role_name' => $user->role->role_name 
            ];
        }

       

        return response()->json([
            'data' => $userData, 
            'total' => $totalUsers,
            'per_page' => $perPage,
            'current_page' => $page,
            'last_page' => ceil($totalUsers / $perPage)
        ]);
    }

    public function destroy($id)
    {

        // Find the user to delete
        $user = User::find($id);

        // Check if the user exists
        if (!$user) {
            return response()->json([
                'error' => 'User not found'
            ], 404);
        }

        // Delete the user
        $user->delete();

        // Return a success response
        return response()->json([
            'message' => 'User deleted successfully'
        ], 200);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:50',
            'role_id' => 'required|exists:roles,id',
            'status' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' =>  $validator->errors()], 400);
        }

        $user = User::find($id);
        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }
        $role = Role::find($request->role_id);

        // validate that the role exists
        if (!$role) {
            return response()->json(['error' => 'Invalid role_id'], 422);
        }

        $user->name = $request->input('name');
        $user->role_id = $request->role_id;
        $user->status = $request->input('status');
        $user->save();

        return response()->json(['message' => 'User updated successfully.'], 200);
    }

    public function setPasswordToDefault($id)
    {
        $user = User::findOrFail($id);
        $tempPassword = $user->password;
        $user->password = $user->default_password;
        $user->default_password = $tempPassword;

        $user->save();

        return response()->json(['message' => 'Password and default password swapped successfully.']);
    }
}
