<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Section;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class CommentController extends Controller {
    public function store(Request $request) {
        $section = Section::query()->where('id', (int)$request->section_id)->first();
        $this->authorize('create', [Comment::class, $section]);

        $validator =  Validator::make($request->all(), [
           'text' => 'required',
           'file' => 'nullable|mimes:pdf|max:5120'
        ]);

        if ($validator->fails()) {
            return redirect(url()->previous() .'#form')
                ->withErrors($validator)
                ->withInput();
        }

        $validated = $validator->validated();
        $comment = new Comment();
        $comment->text = $validated['text'];
        $comment->user_id = Auth::user()->id;
        $comment->section_id = (int)$request->section_id;
        if ($request['reply_id']) {
            $comment->comment_id = (int)$request->reply_id;
        }
        if (isset($validated['file'])) {
            $fileName = time().'_'.$request->file->getClientOriginalName();
            $filePath = $request->file('file')->storeAs('files/', $fileName, 's3');
            Storage::disk('s3')->setVisibility('files/'.$fileName, 'public');
            $comment->file = Storage::disk('s3')->url('files/'.$fileName);
        }
        $comment->save();
        return redirect()
            ->route('section', [(int)$request->section_id]);
    }
}
