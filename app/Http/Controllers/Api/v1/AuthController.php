<?php

namespace App\Http\Controllers\Api\v1;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController 
{
   public function index(Request $request)
   {
        $rules = [
            'email'      => 'required|email',
            'password'   => 'required',
        ];
        
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json([
                'code' => 400,
                'message' => $validator->errors()->first(),
                'status' => 'error',
            ], 400);
        }

        $user = User::where('email', $request->email)->first();
        if ($user || Hash::check($request->password, $user->password)) {
            Auth::login($user);
            $token = $user->createToken('auth_token')->plainTextToken;
            return response()->json([
                'code'    => 200,
                'status'  => 'success',
                'message' => 'Login successful.',
                'token'   => $token,
            ]);
        } else {
            return response()->json([
                'code'    => 401,
                'status'  => 'Unauthorized',
                'message' => 'Invalid email or password.',
            ]);
        }
   }

   public function logout(Request $request)
   {
       if (Auth::check()) {
           Auth::user()->tokens->each(function ($token) {
               $token->delete();
           });

           return response()->json([
               'code'    => 200,
               'status'  => 'success',
               'message' => 'Successfully logged out.'
           ]);
       }

       return response()->json([
           'code'    => 400,
           'status'  => 'error',
           'message' => 'No active session found.'
       ], 400);
   }
}