<?php

namespace App\Http\Controllers;

use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DeparmentController extends Controller {
    public function create(Request $request) {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'text' => 'required',
            'head_of_department' => 'required'
        ]);

        if ($validator->fails()) {
            return redirect(url()->previous())
                ->withErrors($validator)
                ->with('create_error', true)
                ->withInput();
        }

        $validated = $validator->validated();
        $department = new Department();
        $department->name = $validated['name'];
        $department->short_name = $validated['text'];
        $department->user_id = $validated['head_of_department'];
        $department->save();
        return redirect()->route('main_page');
    }

    public function change_department(Request $request) {
        if ($request['action'] == 'update') {
            $validator = Validator::make($request->all(), [
                'name' => 'required',
                'text' => 'required',
                'head_of_department' => 'required'
            ]);

            if ($validator->fails()) {
                return redirect(url()->previous())
                    ->withErrors($validator)
                    ->with('update_error', true)
                    ->with('department_id', $request->department_id)
                    ->withInput();
            }

            $validated = $validator->validated();
            $department = Department::query()->where('id', $request->department_id)->first();
            $department->name = $validated['name'];
            $department->short_name = $validated['text'];
            $department->user_id = $validated['head_of_department'];
            $department->save();
        }
        if ($request['action'] == 'delete') {
            $department = Department::query()->where('id', $request->department_id)->first();
            $department->delete();
        }
        return redirect()->route('main_page');
    }
}
