<?php

namespace App\Http\Controllers;

use App\Models\Section;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SectionController extends Controller
{
    public function show(int $id) {
        $section = Section::query()->where('id', $id)->first();
        if ($section === null) {
            abort(404);
        }
        $subject = $section->subject;
        $comments = $section->comments()->paginate(5);
        $users = array();
        $replies = array();
        foreach ($comments as $comm) {
            $users[] = $comm->user;
            $replies[] = $comm->comment;
        }
        $this->authorize('view', $subject);
        return view('study-section', [ 'section' => $section,
                                            'comments' => $comments,
                                            'users' => $users,
                                            'replies' => $replies]
        );
    }

    public function store(Request $request) {
        $subject = Subject::query()->where('id', (int)$request->discipline_id)->first();
        $this->authorize('create', [Section::class, $subject]);
        $validated = $request->validate([
            'text' => 'required',
            'name' => 'required',
        ]);
        $section = new Section();
        $section->name = $validated['name'];
        $section->text = $validated['text'];
        $section->subject_id = (int)$request->discipline_id;
        $section->save();

        return redirect()->route('discipline', [(int)$section->subject_id]);
    }

    public function get_comments(Request $request, int $id) {
        if ($request->ajax()) {
            $section = Section::query()->where('id', $id)->first();
            if ($section === null) {
                abort(404);
            }
            $comments = $section->comments()->paginate(5);
            $users = array();
            $replies = array();
            foreach ($comments as $comm) {
                $users[] = $comm->user;
                $replies[] = $comm->comment;
            }
            return view('components/comment_pagination', [
                    'comments' => $comments,
                    'users' => $users,
                    'replies' => $replies]
            );
        }
        abort(404);
    }
}
