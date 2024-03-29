<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\View;
use Illuminate\Validation\ValidationException;
use TwigBridge\Facade\Twig;

class RegisterController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\View|RedirectResponse
     * @throws ValidationException
     */
    public function __invoke(Request $request)
    {
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
            if ($user->role === 'student') {
                $user->status = 0;
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
                $student->group_id = $validated['group'];
                $student->save();
            }
            return redirect()
                ->route('login');
        }
        $groups = Group::all();
        return View::make('register', ['groups' => $groups]);
    }
}
