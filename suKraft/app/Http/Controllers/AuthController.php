<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Image;
use App\Models\Media;
use App\Models\UserLogs;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'firstName' => 'required|string|max:255',
            'lastName' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'phoneNo' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }
        $emailToken = Str::random(32);
        global $email;
        global $nameTo;
        $email = $request->email;
        $nameTo = $request->fname . " " . $request->lname;

        $user = User::create([
            'firstName' => $request->firstName,
            'lastName' => $request->lastName,
            'username' => $request->username,
            'phoneNo' => $request->phoneNo,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'mediaId' => null,
            'remember_token' => $emailToken,
            'verified' => false,
            'userRole' => $request->role ?? "user"
        ]);
        //send registration email
        Mail::to($email)->queue(new \App\Mail\RegistrationMail($nameTo, $emailToken));

        $token = $user->createToken('auth_token')->plainTextToken;

        DB::table('users')
            ->where('id', $user->id)
            ->update(['api_token' => $token]);

        return response()
            ->json(['data' => $user, 'access_token' => $token, 'token_type' => 'Bearer',]);
    }

    public function login(Request $request)
    {
        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()
                ->json(['message' => 'Unauthorized'], 401);
        }

        $user = User::where('email', $request['email'])
            ->where('verified',true)
            ->first();

        if (!isset($user)) {
            return response()
                ->json(['message' => 'Not active'], 401);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        DB::table('users')
            ->where('id', $user->id)
            ->update(['api_token' => $token]);

        return response()
            ->json(['message' => 'Hi ' . $user->firstName . ', welcome to home', 'access_token' => $token, 'user' => $user, 'image' => Media::where("id", $user->imageId)->first(), 'token_type' => 'Bearer',]);
    }

    // method for user logout and delete token
    public function logout()
    {
        auth()->user()->tokens()->delete();

        return [
            'message' => 'You have successfully logged out and the token was successfully deleted'
        ];
    }

    // method for activating users
    public function setActiveUser($emailToken)
    {

        DB::table('users')
            ->where('remember_token', $emailToken)
            ->update(['verified' => true, 'remember_token' => null]);

        //url of frontend app/redirect mail
        return redirect("https://www.google.com");
    }

    // method for password forgot
    public function forgotPassword(Request $request)
    {
        $user = User::where("email", $request->email)->first();
        $user->remember_token = Str::random(32);
        $user->save();
        //send registration email
        Mail::to($request->email)->queue(new \App\Mail\ForgotPasswordMail($user->remember_token));
        return true;
    }

}
