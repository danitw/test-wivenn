<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

use App\Models\User;

use Illuminate\Http\JsonResponse;

class AuthController extends Controller
{
    /**
     * Login The User
     * @param Request $request
     * @return User
     */
    public function login(Request $request): JsonResponse
    {
        try {
            $validateUser = Validator::make(
                $request->all(),
                [
                    'email' => 'required|email',
                    'password' => 'required'
                ]
            );

            if ($validateUser->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validateUser->errors()
                ], 401);
            }

            if (!Auth::attempt($request->only(['email', 'password']))) {
                return response()->json([
                    'status' => false,
                    'message' => 'Email & Password does not match with our record.',
                ], 401);
            }

            $user = User::where('email', $request->email)->first();
            //return [$user->password];

            return response()->json([
                'status' => true,
                'message' => 'User Logged In Successfully',
                'token' => $user->createToken("API TOKEN")->plainTextToken
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }
    /**
     * Log the user out of the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request) // TODO: make this later, after of sanctum middleware
    {
        auth()->user()->tokens()->delete();

        return [
            'message' => 'Tokens Revoked'
        ];
        //Auth::guard()->logout();
        //Auth::logoutCurrentDevice();
        //$request->user()->currentAccessToken()->delete();
        //auth()->user()->;


        //Auth::logout();
        ////dd(Auth::hasUser());
        ////Auth::

        //$request->session()->invalidate();

        //$request->session()->regenerateToken();

        //return redirect('/');
    }
}
