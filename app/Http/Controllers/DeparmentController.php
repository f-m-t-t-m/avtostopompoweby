<?php

namespace App\Http\Controllers;

use App\Models\Department;
use Illuminate\Http\Request;

class DeparmentController extends Controller {
    public function create(Request $request) {
        $validated = $request->validate([
            'name' => 'required',
            'text' => 'required',
            'head_of_department' => 'required'
        ]);
        $department = new Department();
        $department->name = $validated['name'];
        $department->short_name = $validated['text'];
        $department->user_id = $validated['head_of_department'];
        $department->save();
        return redirect()->route('main_page');
    }

    public function delete(Request $request) {
        $department = Department::query()->where('id', $request->department_id)->first();
        $department->delete();
    }

    public function update(Request $request) {
        $validated = $request->validate([
            'name' => 'required',
            'text' => 'required',
            'head_of_department' => 'required'
        ]);
        $department = Department::query()->where('id', $request->department_id)->first();
        $department->name = $validated['name'];
        $department->short_name = $validated['text'];
        $department->user_id = $validated['head_of_department'];
        $department->save();
    }

    public function change_department(Request $request) {
        if ($request['action'] == 'update') {
            $this->update($request);
        }
        if ($request['action'] == 'delete') {
            $this->delete($request);
        }
        return redirect()->route('main_page');
    }
}
