<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ConfirmUserController extends Controller {
    public function show() {
        $user = Auth::user();
        if ($user->role !== 'head') {
            abort(403);
        }
        $department = $user->department;
        $groups = $department->groups;
        $users = [];
        $grps = [];
        foreach ($groups as $group) {
            $students = $group->students;
            foreach ($students as $student) {
                if ($student->user->status == 0) {
                    $users[] = $student->user;
                    $grps[] = $student->group->name;
                }
            }
        }
        return view('confirm-student-registration', ['users' => $users,
                                                            'groups' => $grps]);
    }

    public function confirm(int $id) {
        $user = User::query()->where('id', $id)->first();
        $this->authorize('update', $user);
        $user->status = 1;
        $user->save();

        return redirect()->route('confirm-student-registration');
    }
}
