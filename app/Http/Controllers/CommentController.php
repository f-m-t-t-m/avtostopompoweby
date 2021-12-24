<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Section;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class CommentController extends Controller {
    public function store(Request $request) {
        $section = Section::query()->where('id', (int)$request->section_id)->first();
        $this->authorize('create', [Comment::class, $section]);

        $validated = $request->validate([
           'text' => 'required',
           'file' => 'nullable|mimes:pdf|max:5000'
        ]);
        $comment = new Comment();
        $comment->text = $validated['text'];
        $comment->user_id = Auth::user()->id;
        $comment->section_id = (int)$request->section_id;
        if (isset($validated['file'])) {
            $fileName = time().'_'.$request->file->getClientOriginalName();
            $filePath = $request->file('image')->storeAs('files/', $fileName, 's3');
            $comment->file = Storage::disk('s3')->url('files/'.$fileName);
        }
        $comment->save();
        return redirect()
            ->route('section', [(int)$request->section_id]);
    }
}
