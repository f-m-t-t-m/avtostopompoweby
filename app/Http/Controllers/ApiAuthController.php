<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\Group;
use App\Models\Student;
use App\Models\User;
use http\Env\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
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
            'avatar' => 'nullable|mimes:jpeg,jpg,png|max:10000',
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

        if (isset($validated['image'])) {
            $fileName = time().'_'.$request->image->getClientOriginalName();
            $filePath = $request->file('image')->storeAs('avatars/', $fileName, 's3');
            Storage::disk('s3')->setVisibility('avatars/'.$fileName, 'public');
            $user->avatar = Storage::disk('s3')->url('avatars/'.$fileName);
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
        return response()->json(['message' => 'Logged out'], 200);
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
        $user = User::query()->where('email', $validated['email'])->first();
        if ($user->status == 0) {
            return response()->json(['message' => 'User isn\'t confirmed'], 423);
        }
        if (!Auth::attempt(['email' => $validated['email'], 'password' => $validated['password']])) {
            return response()->json(['message' => 'Wrong login or password'], 422);
        }

        $token = $user->createToken('token')->plainTextToken;
        $response = [
            'user' => UserResource::make($user),
            'token' => $token,
        ];

        return response()->json($response, 201);
    }
}
