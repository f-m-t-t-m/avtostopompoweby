<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Http\Request;

class SubjectController extends Controller {
    public function show(int $id) {
        $subject = Subject::query()->where('id', $id)->first();
        $this->authorize('view', $subject);
        if ($subject === null) {
            abort(404);
        }
        $teacher = $subject->user;
        $group = $subject->group;
        $sections = $subject->sections;
        return view('discipline', ['subject' => $subject,
            'teacher' => $teacher,
            'group' => $group,
            'sections' => $sections]);
    }

    public function show_create_form(int $id) {
        $teachers = User::query()->where('role', 'teacher')->get();
        $group = Group::query()->where('id', $id)->first();
        $this->authorize('create', [Subject::class, $group]);
        return view('create-disciplines', ['teachers' => $teachers,
                                                'group' => $group]);
    }

    public function create_subject(Request $request) {
        $group = Group::query()->where('id', (int)$request->group_id)->first();
        $this->authorize('create', [Subject::class, $group]);
        $validated = $request->validate([
            'name' => 'required'
        ]);
        $subject = new Subject();
        $subject->name = $validated['name'];
        $subject->user_id = (int)$request->teacher;
        $subject->group_id = (int)$request->group_id;
        $subject->save();
        return redirect()->route('main_page');
    }
}
