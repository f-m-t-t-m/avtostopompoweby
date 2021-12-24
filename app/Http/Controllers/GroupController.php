<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Group;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class GroupController extends Controller {
    public function show_create_form(int $id) {
        $department = Department::query()->where('id', $id)->first();
        return view('create-group', ['department' => $department]);
    }

    public function create_group(Request $request) {
        $validator = Validator::make($request->all(), [
            'full_name' => 'required',
            'short_name' => 'required',
            'code' => 'required',
        ]);

        $validated = $validator->validated();
        $group = new Group();
        $group->name = $validated['full_name'];
        $group->short_name = $validated['short_name'];
        $group->code = $validated['code'];
        $group->department_id = $request->department_id;
        $group->save();

        return redirect()->route('main_page');
    }
}
