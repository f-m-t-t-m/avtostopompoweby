<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\Student;
use App\Models\User;
use http\Env\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ApiAuthController extends Controller
{
    public function register(Request $request) : JsonResponse {
        $request['email'] = strtolower($request['email']);
        $validator = Validator::make($request->all(), [
            'email' => 'required|unique:users|email',
            'password' => 'required|between:10, 30|regex: /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&].{10,}$/',
            'name' => 'required',
            'surname' => 'required',
            'role' => 'required',
            'group' => Rule::requiredIf($request->role === 'student'),
            'avatar' => 'nullable',
        ]);

        if ($validator->fails()) {
            $messages = $validator->errors()->all();
            return response()->json(['message' => $messages], 422);
        }

        $validated = $validator->validated();
        $user = new User();
        $user->email = $validated['email'];
        $user->password = Hash::make($validated['password']);
        $user->name = $validated['name'];
        $user->surname = $validated['surname'];
        $user->role = $validated['role'];
        $user->status = 1;
        if (isset($validated['avatar'])) {
            $user->avatar = $validated['avatar'];
        }
        $user->save();
        if ($user->role === 'student') {
            $student = new Student();
            $student->user_id = $user->id;
            $student->group_id = Group::query()->where('name', $validated['group'])->first()->id;
            $student->save();
        }

        $token = $user->createToken('token')->plainTextToken;
        $response = [
            'user' => $user,
            'token' => $token,
        ];
        return response()->json($response, 201);
    }

    public function logout() : JsonResponse {
        Auth::user()->tokens()->delete();
        return response()->json(['message' => 'Logged out']);
    }

    public function login(Request $request): JsonResponse {
        $request['email'] = strtolower($request['email']);
        $validator = Validator::make($request->all(), [
            'email' => 'required',
            'password' => 'required|max:30'
        ]);

        if ($validator->fails()) {
            $messages = $validator->errors()->all();
            return response()->json(['message' => $messages], 422);
        }

        $validated = $validator->validated();
        if (!Auth::attempt(['email' => $validated['email'], 'password' => $validated['password']])) {
            return response()->json(['message' => 'Wrong login or password'], 422);
        }

        $user = User::query()->where('email', $validated['email'])->first();
        $token = $user->createToken('token')->plainTextToken;
        $response = [
            'user' => $user,
            'token' => $token,
        ];

        return response()->json($response, 201);
    }
}
