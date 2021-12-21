<?php

namespace App\Http\Controllers;

use App\Models\Section;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SectionController extends Controller
{
    public function store(Request $request) {
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
}