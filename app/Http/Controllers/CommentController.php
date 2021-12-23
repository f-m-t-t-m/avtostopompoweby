<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller {
    public function store(Request $request) {
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
            $filePath = $request->file('file')->storeAs('uploads', $fileName, 'public');
            $comment->file = '/storage/' . $filePath;
        }
        $comment->save();
        return redirect()
            ->route('section', [(int)$request->section_id]);
    }
}
