<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param Request $request
     * @return Application|Factory|View|RedirectResponse
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

            $user = User::query()->where('email', $validated['email'])->first();
            if ($user === null) {
                return redirect()
                    ->route('login')->with('error', 'Неправильный логин или пароль');
            }
            if ($user->status == 0) {
                return redirect()
                    ->route('login')->with('error', 'Неподтвержденный аккаунт');
            }

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
