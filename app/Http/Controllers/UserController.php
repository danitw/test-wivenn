<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

use App\Models\User;

use Illuminate\Http\JsonResponse;

class UserController extends Controller
{
    /**
     * Create User
     * @param Request $request
     * @return User
     */
    public function create(Request $request): JsonResponse
    {
        try {
            //Validated
            $validateUser = Validator::make(
                $request->all(),
                [
                    'username' => 'required',
                    'email' => 'required|email|unique:users,email',
                    'password' => 'required|min:8|max:32',
                ]
            );

            if ($validateUser->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validateUser->errors()
                ], 401);
            }

            $user = User::create([
                'username' => $request->username,
                'email' => $request->email,
                //'password' => $request->password,
                'password' => Hash::make($request->password),
                'isAdmin' => $request->isAdmin | false,
                'entryDate' => Carbon::now()

            ]);

            return response()->json([
                'status' => true,
                'message' => 'User Created Successfully',
                'token' => $user->createToken("API TOKEN")->plainTextToken
            ], 201);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    /**
     * Read User
     * @param Request $request
     * @return User
     */
    public function read(Request $request, $id): JsonResponse
    {
        try {
            $user = User::find($id);

            return response()->json([
                'status' => true,
                'message' => 'User Found Successfully',
                'user' => $user
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    /**
     * Update User
     * @param Request $request
     * @return User
     */
    public function update(Request $request, $id): JsonResponse
    {
        try {
            //Validated
            $validateUser = Validator::make(
                $request->all(),
                [
                    'email' => 'email|unique:users,email',
                    'password' => 'min:8|max:32',
                ]
            );

            if ($validateUser->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validateUser->errors()
                ], 401);
            }

            $body = $request->all();

            if ($body['password']) {
                $body['password'] = Hash::make($body['password']);
            }

            User::where('id', $id)->update($body);

            return response()->json([
                'status' => true,
                'message' => 'User Updated Successfully',
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    /**
     * Delete User
     * @param Request $request
     * @return User
     */
    public function delete(Request $request, $id): JsonResponse
    {
        try {
            User::where('id', $id)->delete();

            return response()->json([
                'status' => true,
                'message' => 'User Deleted Successfully',
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }
}
