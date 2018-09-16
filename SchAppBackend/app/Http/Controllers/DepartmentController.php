<?php

namespace App\Http\Controllers;

use App\Models\Department;
use Illuminate\Http\Request;
use Validator;

class DepartmentController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
        public function __construct()
    {
        // $this->middleware('super_admin')->only(['create', 'delete']);
    }

    public function create()
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required'
        ]);

        if($validator->fails()){
            return response()->json([
                'status' => 'error',
                'data' => $validator->errors()], 200);
        }

        $department = Department::create([
            'name' => $request->name,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'department successfully created',
            'data' => $department], 200);
    }

    public function delete(Department $department)
    {
        $id = $department->id;
        $department->delete();
        return response()->json([
            'status' => 'success',
            'message' => 'department successfully deleted',
            'data' => $department->id], 200);
    }

    public function posts(Department $department)
    {
        $posts = $department->posts()->with('user')->paginate(10);
        return response()->json([
            'status' => 'success',
            'data' => $posts], 200);
    }

    public function polls(Department $department)
    {
        $polls = $department->polls()->with('user')->paginate(10);
        return response()->json([
            'status' => 'success',
            'data' => $polls], 200);
    }

    public function users(Department $department)
    {
        $users = $department->users()->paginate(10);
        return response()->json([
            'status' => 'success',
            'data' => $users], 200);
    }
}
