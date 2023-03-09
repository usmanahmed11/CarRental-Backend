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
        // Validate the input data using Laravel's built-in validator
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => ['required', 'string', 'min:8'],
            'role_id' => 'required|exists:roles,id',
        ]);
        // If validation fails, return an error response
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }
        // Find the role based on the given role_id
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
        // Set the user's role_id and save the user
        $user->role_id = $request->role_id;
        $user->save();

        // Create a token for the user and save it
        $token = $user->createToken('authToken')->accessToken;
        $user->remember_token = $token;
        $user->save();

        // Send an activation email to the user
        Mail::to($user->email)->send(new UserActivationEmail($token));

        // Return a success response with a message

        return response()->json(['message' => 'Account Created Successfully Please Verify The Mail For Login'], 200);
    }


    public function index()
    {
        // Get all the roles using the Role model
        $roles = Role::all();

        // Return the roles as a JSON response
        return response()->json($roles);
    }
    public function getEmail($id)
    {
        // Retrieve the user with the given ID, or throw an exception if it doesn't exist
        $user = User::findOrFail($id);

        // Retrieve the role associated with the user's role ID, or throw an exception if it doesn't exist
        $role = Role::findOrFail($user->role_id);

        // Return the email and user information as a JSON response
        return response()->json([
            'email' => $user->email,
            'name' => $user->name,
            'role_id' => $user->role_id,
            'role_name' => $role->role_name
        ]);
    }


    public function activateAccount(Request $request)
    {
        // Validate the new password using Laravel's validator
        $validator = Validator::make($request->all(), [
            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*()\-_=+{};:,<.>]).+$/'
            ],
            'password_confirmation' => ['required', 'same:password'],
        ], [

            'password.string' => 'Password must be a string',
            'password.min' => 'Password must be at least 8 characters',
            'password.confirmed' => 'Password confirmation does not match',
            'password.regex' => 'Password must contain at least one uppercase letter, one lowercase letter, one number, and one special character',
            'password_confirmation.same' => 'Password confirmation does not match',
        ]);

        // If validation fails, return error response with validation errors
        if ($validator->fails()) {
            $errors = $validator->messages()->all();
            return response()->json(['error' => $errors], 422);
        }
        // Find the user using the activation token
        $user = User::where('remember_token', $request->token)->first();

        // If the user is not found, return an error message
        if (!$user) {
            return response()->json(['error' => 'Invalid activation link.'], 400);
        }

        // Update the user's password, set the activation token to null, and mark their email as verified
        $user->password = Hash::make($request->password);
        $user->remember_token = null;
        $user->email_verified_at = Carbon::now();
        $user->save();
        // Return a success response with a message
        return response()->json(['message' => 'Account activated successfully. Now You Can Login With Your New Password'], 200);
    }


    public function showUsers(Request $request)
    {
        // Set the number of users to send per page
        $perPage = 10;
        // Get the current page number from the query string or default to 1
        $page = $request->query('page') ?: 1;
        // Retrieve the users for the current page
        $users = User::latest()->skip(($page - 1) * $perPage)->take($perPage)->get();
        // Get the total number of users
        $totalUsers = User::count();
        // Transform the user data to the desired format
        $userData = [];
        foreach ($users as $user) {
            $userData[] = [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'status' => $user->status,
                'role_name' => $user->role->role_name
            ];
        }


        // Return the user data as a JSON response
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
        // Validate the input data using Laravel's built-in validator
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:50',
            'role_id' => 'required|exists:roles,id',
            'status' => 'required',
        ]);
        // If validation fails, return an error response
        if ($validator->fails()) {
            return response()->json(['error' =>  $validator->errors()], 400);
        }
        // Find the user to update
        $user = User::find($id);
        // If the user is not found, return an error response
        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }
        // Find the role to update
        $role = Role::find($request->role_id);

        // validate that the role exists
        if (!$role) {
            return response()->json(['error' => 'Invalid role_id'], 422);
        }
        // Update the user's information
        $user->name = $request->input('name');
        $user->role_id = $request->role_id;
        $user->status = $request->input('status');
        $user->save();
        // Return a success message
        return response()->json(['message' => 'User updated successfully.'], 200);
    }

    public function setPasswordToDefault($id)
    {
        // Find the user with the given ID.
        $user = User::findOrFail($id);
        // Store the user's current password in a temporary variable.
        $tempPassword = $user->password;
        // Set the user's password to their default password.
        $user->password = $user->default_password;
        // Set the user's default password to their previous password.
        $user->default_password = $tempPassword;
        // Save the changes to the user object in the database.
        $user->save();
        // Return a JSON response with a success message.
        return response()->json(['message' => 'Password and default password swapped successfully.']);
    }
}
