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
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }
        // attempt to authenticate the user
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {

            // create an access token for the authenticated user
            $user = Auth::user();
            if ($user->email_verified_at == null) {
                return response()->json(['error' => 'User is Not Verified'], 401);
            }
            
            // Get the role_id of the user
            $role_id = $user->role_id;

            $token = $user->createToken('authToken')->accessToken;

            return response()->json(['access_token' => $token, 'user' => $user->email , 'role_id' => $role_id], 200);
        } else {
            return response()->json(['error' => 'Email or Password is Incorrect'], 401);
        }
    }


    public function logout(Request $request)
    {

        if (auth()->check()) {
            // delete the token of the authenticated user

            $request->user()->tokens()->delete();

            return response()->json(['message' => 'Successfully logged out']);
        } else {
            return response()->json(['message' => 'user not logged in ']);
        }
    }

    public function changePassword(Request $request)
    {
        if (auth()->check()) {
            $user = auth()->user();

            if (!Hash::check($request->old_password, $user->password)) {
                return response()->json(['error' => 'Old password does not match'], 400);
            }
            $validator = Validator::make($request->all(), [
                'password' => ['required', 'string', 'min:8', 'confirmed'],
                'password_confirmation' => ['required', 'same:password'],
            ]);

            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 422);
            }

            $user->password = bcrypt($request->password);
            $user->save();
            return response()->json(['message' => 'Password changed successfully'], 200);
        } else {

            return response()->json(['error' => 'User is Not Authenticated'], 401);
        }
    }


    public function sendPasswordResetLink(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $user = User::where('email', $request->email)->first();


        if (!$user) {
            return response()->json(['error' => 'Email Is Invalid'], 400);
        }

        $token = $user->createToken('authToken')->accessToken;
        $user->remember_token = $token;
        $user->save();

        try {
            Mail::to($request->email)->send(new sendPasswordResetLink($token));
        } catch (\Throwable $th) {
            return response()->json(['message' => $th->getMessage()], 400);
        }

        return response()->json(['message' => 'Mail Sent Successfully. Please Check Your Email'], 200);
    }

    public function resetPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'password' =>  ['required', 'string', 'min:8'],

        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }
        $token = $request->token;


        $user = User::where('remember_token', $token)->first();

        if (!$user) {
            return response()->json(['error' => 'Invalid token'], 400);
        }

        $user->password = Hash::make($request->password);
        $user->remember_token = null;
        $user->save();

        return response()->json(['message' => 'Password reset successfully'], 200);
    }

    public function show()
    {
        $user = Auth::user();

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
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'profile_picture' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        $user = Auth::user();


        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }


        $user->fill($request->only('name'));

        if ($request->hasFile('profile_picture')) {
            $image = $request->file('profile_picture');

            // generate a unique file name for the profile picture
            $fileName = time() . '.' . $image->getClientOriginalExtension();

            // set the path for storing the profile picture
            $path = 'profile_pictures/' . $fileName;

            // save the profile picture to the desired path
            Image::make($image)->save(public_path($path));

            $user->profile_picture = $fileName;
        }
        $user->save();
        return response()->json(['message' => 'Profile Updated Successfully'], 200);
    }
}
