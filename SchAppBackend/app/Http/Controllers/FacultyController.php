<?php

namespace App\Http\Controllers;

use App\Models\Faculty;
use Illuminate\Http\Request;
use Validator;

class FacultyController extends Controller
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

        $faculty = Faculty::create([
            'name' => $request->name,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'faculty successfully created',
            'data' => $faculty], 200);
    }

    public function delete(Faculty $faculty)
    {
        $id = $faculty->id;
        $faculty->delete();
        return response()->json([
            'status' => 'success',
            'message' => 'faculty successfully deleted',
            'data' => $faculty->id], 200);
    }

    public function posts(Faculty $faculty)
    {
        $posts = $faculty->posts()->with('user')->paginate(10);
        return response()->json([
            'status' => 'success',
            'data' => $posts], 200);
    }

    public function polls(Faculty $faculty)
    {
        $polls = $faculty->polls()->with('user')->paginate(10);
        return response()->json([
            'status' => 'success',
            'data' => $polls], 200);
    }

    public function users(Faculty $faculty)
    {
        $users = $faculty->users()->paginate(10);
        return response()->json([
            'status' => 'success',
            'data' => $users], 200);
    }
}
