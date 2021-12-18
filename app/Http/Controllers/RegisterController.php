<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class RegisterController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request) {
        if (Auth::check()) {
            return redirect()
                ->route('main_page');
        }

        if ($request->isMethod('post')) {
            $request['email'] =strtolower($request['email']);
            $validator = Validator::make($request->all(), [
                'email' => 'required|unique:users|email',
                'password' => 'required|between:10, 30|regex: /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&].{10,}$/',
                'name' => 'required',
                'surname' => 'required',
                'role' => 'required',
                'group' => Rule::requiredIf($request->role === 'student'),
                'image' => 'required|mimes:jpeg,jpg,png|max:10000',
            ], [
                'password.regex' => 'В пароле должна быть хотя бы одна цифра, хотя бы одна буква в каждом регистре и хотя бы один знак препинания',
                'group.required' => 'Необходимо выбрать группу',
                'email.unique' => 'Эта почта уже занята',
                'email.required' => 'Необходимо заполнить почту',
                'name.required' => "Необходимо заполнить имя",
                'surname.required' => 'Необходимо заполнить фамилию',
                'password.required' => 'Необходимо заполнить пароль'
            ]);

            $validated = $validator->validated();
            $user = new User();
            $user->email = $validated['email'];
            $user->password = Hash::make($validated['password']);
            $user->name = $validated['name'];
            $user->surname = $validated['surname'];
            $user->role = $validated['role'];
            $user->status = 1;
            if (isset($validated['image'])) {
                $fileName = time().'_'.$request->image->getClientOriginalName();
                $filePath = $request->file('image')->storeAs('uploads', $fileName, 'public');
                $user->avatar = '/storage/' . $filePath;
            }
            $user->save();
            if ($user->role === 'student') {
                $student = new Student();
                $student->user_id = $user->id;
                $student->group_id = Group::query()->where('name', $validated['group'])->first()->id;
                $student->save();
            }
            Auth::login($user);
            return redirect()
                ->route('main_page');
        }
        return view('register');
    }
}
