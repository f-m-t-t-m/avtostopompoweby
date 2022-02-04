<?php

namespace App\Http\Controllers;

use App\Events\NewMessage;
use App\Events\PushNotification;
use App\Http\Resources\CommentResource;
use App\Models\Comment;
use App\Models\Notification;
use App\Models\Section;
use App\Models\Subject;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ApiCommentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Subject $subject, Section $section) : JsonResponse
    {
        $comments = $section->comments;
        return response()->json(CommentResource::collection($comments),200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Subject $subject, Section $section, Request $request): JsonResponse {
        $validator = Validator::make($request->all(), [
            'text' => 'required|max:150',
            'file' => 'nullable'
        ]);

        if ($validator->fails()) {
            $messages = $validator->errors()->all();
            return response()->json(['message' => $messages], 422);
        }
        $validated = $validator->validated();
        $comment = new Comment();
        $comment->text = $validated['text'];
        $comment->user_id = Auth::user()->id;
        $comment->section_id = $section->id;
        if ($request->reply) {
            $comment->comment_id = $request->reply;
        }

        if (isset($validated['file'])) {
            $pdf = base64_decode($validated['file']);
            $fullFileName = 'files/'.time().'_'.Auth::user()->name.Auth::user()->surname.'.pdf';
            Storage::disk('s3')->put($fullFileName, $pdf);
            Storage::disk('s3')->setVisibility($fullFileName, 'public');
            $comment->file = Storage::disk('s3')->url($fullFileName);
        }

        $comment->save();

        $comments = $section->comments()->paginate(5);
        $lastPage = $comments->lastPage();
        $users = [];
        $users[] = $section->subject->user;
        $users[] = $section->subject->group->department->user;
        $students = $section->subject->group->students;
        foreach ($students as $student) {
            $users[] = $student->user;
        }
        foreach ($users as $user) {
            if ($user->id != Auth::user()->id) {
                NewMessage::dispatch($section, $subject, $comment, $lastPage, $user->id);
                if($user->fcm_token) {
                    $user->notify(new PushNotification($section, $subject));
                }
                $notification = new Notification();
                $notification->user_id = $user->id;
                $notification->subject_id = $subject->id;
                $notification->section_id = $section->id;
                $notification->text = sprintf('Новое сообщение в разделе: %s предмета: %s',
                    $section->name, $subject->name);
                $notification->save();
            }
        }

        return response()->json(new CommentResource($comment), 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Subject $subject, Section $section, Comment $comment) : JsonResponse
    {
        return response()->json(new CommentResource($comment), 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
