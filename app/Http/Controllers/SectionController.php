<?php

namespace App\Http\Controllers;

use App\Models\Section;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

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
        $validator = Validator::make ($request->all(), [
            'text' => 'required',
            'name' => 'required',
            'file' => 'nullable|mimes:pdf|max:5120',
        ]);

        if ($validator->fails()) {
            return redirect(url()->previous())
                ->withErrors($validator)
                ->with('create_error', true)
                ->withInput();
        }
        $validated = $validator->validated();
        $section = new Section();
        $section->name = $validated['name'];
        $section->text = $validated['text'];
        if (isset($validated['file'])) {
            $fileName = time().'_'.$request->file->getClientOriginalName();
            $filePath = $request->file('file')->storeAs('files/', $fileName, 's3');
            Storage::disk('s3')->setVisibility('files/'.$fileName, 'public');
            $section->file = Storage::disk('s3')->url('files/'.$fileName);
        }

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
