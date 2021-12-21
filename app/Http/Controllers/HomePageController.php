<?php

namespace App\Http\Controllers;

use App\Http\Resources\GroupResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomePageController extends Controller
{
    public function show() {
        $user = Auth::user();
        if ($user->role === 'student') {
            $teachers = array();
            $groups = array();
            $subjects = $user->student->group->subjects;
            foreach ($subjects as $subject) {
                $teachers[] = $subject->user;
                $groups[] = $subject->group;
            }
            return view('home-student', ['subjects' => $subjects,
                                              'teachers' => $teachers,
                                              'groups' => $groups]);
        }

        if ($user->role === 'teacher') {
            $teachers = array();
            $groups = array();
            $subjects = $user->subjects;
            foreach ($subjects as $subject) {
                $teachers[] = $subject->user;
                $groups[] = $subject->group;
            }
            return view('home-teacher', ['subjects' => $subjects,
                'teachers' => $teachers,
                'groups' => $groups]);
        }

        if ($user->role === 'head') {
            $department_groups = $user->department->groups;
            $subjects = array();
            $teachers = array();
            $groups = array();
            foreach ($department_groups as $group) {
                foreach ($group->subjects as $subject) {
                    $subjects[] = $subject;
                    $teachers[] = $subject->user;
                    $groups[] = $subject->group;
                }
            }
            return view('home-head', ['subjects' => $subjects,
                                            'teachers' => $teachers,
                                            'groups' => $groups,
                                            'department_groups' => $department_groups]);
        }
    }
}
