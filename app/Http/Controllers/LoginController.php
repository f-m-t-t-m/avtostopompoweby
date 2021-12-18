<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        if (Auth::check()) {
            return redirect()
                ->route('main_page');
        }

        if ($request->isMethod('post')) {
            $request['email'] = strtolower($request['email']);
            $validated = $request->validate([
                'email' => 'required|email',
                'password' => 'required',
            ]);

            if (Auth::attempt($validated)) {
                $request->session()->regenerate();
                return redirect()
                    ->route('main_page');
            }
            return redirect()
                ->route('login')->with('error', 'Неправильный логин или пароль');
        }

        return view('login');
    }
}
