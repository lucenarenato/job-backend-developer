<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class PassportController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|min:3',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
        ]);

        if ($validator->fails()) {
            return response(['errors' => $validator->errors()->all()], 422);
        }
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);
        $row = [];
        $row = [
            'name' => $user->name,
            'email' => $user->email,
            'token' => $user->createToken('Laravel Password Grant Client')->accessToken,
        ];

        return response()->json($row, Response::HTTP_CREATED);
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:6',
        ]);
        if ($validator->fails()) {
            return response(['errors' => $validator->errors()->all()], 422);
        }
        $response = [];
        $max_attemps_allowed = 3;


        $user = User::where('email', $request->email)->first();
        if (isset($user)) {
            $user->attempts = $user->attempts + 1;
            $user->save();

            if ($user->attempts >= $max_attemps_allowed) {
                $user->islocked = true;
                $user->lock_date = Carbon::now()->format('Y-m-d H:i:s');
                $user->save();
            }

            if ($user->islocked == true) {
                $response = ["message" => 'User does not autorization'];
                return response()->json($response, 401);
            }

            if (Hash::check($request->password, $user->password)) {
                $token = $user->createToken('Laravel Password Grant Client')->accessToken;
                $user->tokenvalidation = $token;
                $user->attempts = 0;
                $user->last_access = Carbon::now();
                $user->save();
                $response = ['token' => $token];
                return response($response, 200);
            } else {
                $response = ["message" => "Password mismatch"];
                return response($response, 422);
            }
        } else {
            $response = ["message" => 'Invalid Credentials'];
            return response()->json($response, 401);
        }
    }

    public function logout(Request $request)
    {
        $token = $request->user()->token();
        $token->revoke();
        $response = ['message' => 'You have been successfully logged out!'];
        return response($response, 200);
    }
}
