<?php

namespace App\Http\Controllers;

use App\Mail\sendPasswordResetLink;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;
use Laravel\Passport\HasApiTokens;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    use HasApiTokens;
    public function login(Request $request)
    {
        // Validate the input data using Laravel's built-in validator
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);
        // If validation fails, return an error response
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        // Check if the user is active
        $user = User::where('email', $request->email)->first();
        if ($user && $user->status === 'inactive') {
            return response()->json(['error' => 'User is inactive'], 401);
        }

        // Attempt to authenticate the user with the provided email and password
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {

            // Get the authenticated user
            $user = Auth::user();

            // Check if the user's email has been verified
            if ($user->email_verified_at == null) {
                return response()->json(['error' => 'User is Not Verified'], 401);
            }

            // Get the role_id of the user
            $role_id = $user->role_id;

            // Create an access token for the authenticated user
            $token = $user->createToken('authToken')->accessToken;


            // Return a success response with the access token, user's email, and role_id
            return response()->json(['access_token' => $token, 'user' => $user->email, 'role_id' => $role_id], 200);
        } else {
            // If authentication fails, return an error response
            return response()->json(['error' => 'Email or Password is Incorrect'], 401);
        }
    }


    public function logout(Request $request)
    {

        // Check if the user is authenticated
        if (auth()->check()) {
            // Delete the token of the authenticated user
            $request->user()->tokens()->delete();
            // Return a success message upon successful logout
            return response()->json(['message' => 'Successfully logged out']);
        } else {
            // Return an error message if user is not logged in
            return response()->json(['message' => 'user not logged in ']);
        }
    }

    public function changePassword(Request $request)
    {
        // Check if the user is authenticated
        if (auth()->check()) {

            // Get the authenticated user object
            $user = auth()->user();
            // Check if the old password matches with the user's current password
            if (!Hash::check($request->old_password, $user->password)) {
                return response()->json(['error' => 'Old password does not match'], 400);
            }

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


            // If validation passes, update the user's password and save the changes
            $user->password = bcrypt($request->password);
            $user->save();
            // Return success message upon successful password change
            return response()->json(['message' => 'Password changed successfully'], 200);
        } else {
            // If the user is not authenticated, return an error response
            return response()->json(['error' => 'User is Not Authenticated'], 401);
        }
    }


    public function sendPasswordResetLink(Request $request)
    {
        // Validate the email provided in the request
        $request->validate([
            'email' => 'required|email',
        ]);
        // Get the user with the email provided in the request
        $user = User::where('email', $request->email)->first();

        // If user does not exist, return error response
        if (!$user) {
            return response()->json(['error' => 'Email Is Invalid'], 400);
        }
        // Create a new token for the user and update the user's remember_token
        $token = $user->createToken('authToken')->accessToken;
        $user->remember_token = $token;
        $user->save();
        // Try sending an email to the user with the password reset link
        try {
            Mail::to($request->email)->send(new sendPasswordResetLink($token));
        } catch (\Throwable $th) {
            // If sending email fails, return error response with the error message
            return response()->json(['message' => $th->getMessage()], 400);
        }
        // Return success response upon successful email send
        return response()->json(['message' => 'Mail Sent Successfully. Please Check Your Email'], 200);
    }

    public function resetPassword(Request $request)
    {
        // Validate the password provided in the request
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

        // Get the token from the request
        $token = $request->token;

        // Get the user with the provided token
        $user = User::where('remember_token', $token)->first();

        // If user does not exist with the provided token, return error response
        if (!$user) {
            return response()->json(['error' => 'Invalid token'], 400);
        }

        // Reset the user's password and remember_token, and save the user
        $user->password = Hash::make($request->password);
        $user->remember_token = null;
        $user->save();

        // Return success response upon successful password reset
        return response()->json(['message' => 'Password reset successfully'], 200);
    }

    public function show()
    {
        // Get the authenticated user
        $user = Auth::user();

        // Return the user's name, profile picture, email, role id, and status in a JSON response
        return response()->json([
            'name' => $user->name,
            'profile_picture' => $user->profile_picture,
            'email' => $user->email,
            'role_id' => $user->role_id,
            'status' => $user->status,
        ], 200);
    }

    public function updateProfile(Request $request)
    {
        // Validate the input data using Laravel's built-in validator
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'profile_picture' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ]);

        // if validation fails, return an error response
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }
        // get the authenticated user
        $user = Auth::user();

        // if the user is not authenticated, return an unauthorized error response
        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        // update the user's name with the input name
        $user->fill($request->only('name'));
        // if a new profile picture is uploaded, update the user's profile picture
        if ($request->hasFile('profile_picture')) {
            $image = $request->file('profile_picture');

            // generate a unique file name for the profile picture
            $fileName = time() . '.' . $image->getClientOriginalExtension();

            // set the path for storing the profile picture
            // $path = 'profile_pictures/' . $fileName;
            $path = $image->storeAs('public/profile_pictures', $fileName);


            // save the profile picture to the desired path
            // Image::make($image)->save(public_path($path));

            Image::make($image)->save(storage_path('app/' . $path));

            // update the user's profile picture with the new file name
            $user->profile_picture = $fileName;
        }
        // return a success response
        $user->save();
        return response()->json(['message' => 'Profile Updated Successfully'], 200);
    }
}
