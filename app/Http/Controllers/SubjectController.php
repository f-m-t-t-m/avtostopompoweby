<?php

namespace App\Http\Controllers;

use App\Models\Subject;
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
}
