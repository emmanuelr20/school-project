<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Validator;
use Firebase\JWT\JWT;


class AuthController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Create a new controller instance.
     *
     * @return json
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required',
            'last_name' => 'required',
            'telephone' => 'numeric',
            'staff_id' => 'required|unique:users',
            'email' => 'required|unique:users',
            'password' => 'required',
            'department_id' => 'required',
        ]);

        if($validator->fails()){
            return response()->json([
                    'status' => 'error',
                    'data' => $validator->errors()], 200);
        }

        $user = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'middle_name' => $request->has('middle_name') ? $request->middle_name : '',
            'department_id' => $request->department_id,
            'telephone' => $request->telephone,
            'staff_id' => $request->staff_id,
            'email' => $request->email,
            'password' => app('hash')->make($request->password),
        ]);

        return response()->json([
                    'status' => 'success',
                    'message' => 'User created successfully!',
                    'user' => $user], 200);
    }

    public function login(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'credential' => 'required',
            'password' => 'required',
        ]);

        if($validator->fails()){
            return response()->json([
                    'status' => 'error',
                    'data' => $validator->errors()], 200);
        }
        
        if (filter_var($request->credential, FILTER_VALIDATE_EMAIL)) {
            $user = $this->user->where('email', $request->credential)->first();
        } else {
            $user = $this->user->where('staff_id', $request->credential)->first();
        }
        
        if (!$user || !app('hash')->check($request->password, $user->password)) {
            return response()->json([
                'status' => 'error',
                'message' => 'invalid login credential!'
            ], 200);
        }

        $exp = $request->has('remember')? strtotime('now +1 years') : strtotime('now +6 hours');

        $token_arr = [
            "iss" => env("APP_URL"),
            "iat" => time(),
            "nbf" => strtotime('now -2 minutes'),
            "exp" => $exp,
            'data' => $user
        ];

        $token = JWT::encode($token_arr, env('APP_KEY'));

        $request->request->set('api_token', $token);

        return response()->json([
            'status' => 'success',
            'message' => 'Login Successful!',
            'token' => $token,
            'user' => \Auth::user()
        ], 200);
    }

    public function logout(Request $request)
    {
        return null;
    }

    public function getTokenUser(Request $request)
    {
        if (\Auth::check()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Login Successful!',
                'token' => $request->api_token,
                'user' => \Auth::user()
            ], 200);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed Authentication!',
            ], 200);
        }
        
    }
}
