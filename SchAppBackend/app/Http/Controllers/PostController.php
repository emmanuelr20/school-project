<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Image;
use Validator;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class PostController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'body' => 'required',
            'image' => 'mimes:jpg,jpeg,png,gif'
        ]);

        if($validator->fails())
            return response()->json(['status' => 'error', 'message' => $validator->errors()], 401);

        $post = Post::create([
            'title' => $request->title,
            'body' => $request->body,
            'access_level' => $request->has('access_level') ? $request->access_level : null,
            'department_id' => $request->has('department_id') ? $request->department_id : null,
            'faculty_id' => $request->has('faculty_id') ? $request->faculty_id : null,
            'role_id' => $request->has('role_id') ? $request->role_id : null,
            'user_id' => \Auth::user()->id,
        ]);

        if( !empty($request->file('image')) ) {
            $post->image_url = ImageController::store($request->file('image'), 'post', $post->id);
            $post->save();
        }
        
        return response()->json(['status' => 'success', 'post' => $post], 200);
    }

    public function delete($id)
    {
        $post = Post::where('id', $id)->first();
        if (!auth()->user()->id == $post->user->id || auth()->user()->isSuperAdmin()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized'
            ], 401);
        }
        $post->delete();
        return response()->json([
            'status' => 'success'], 200); 
    }

    public function get($id)
    {
        $post = Post::where('id', $id)->with([
            'user'=> function($q){
                $q->select([
                    'id', 'first_name', 'last_name', 'email', 'avatar_url', 'staff_id'
                ]);
            }, 'faculty', 'department', 
            'comments' => function($q){
                $q->orderByDesc('created_at')->with([
                    'user'=> function($q){
                        $q->select([
                            'id', 'first_name', 'last_name', 'email', 'avatar_url', 'staff_id'
                        ]);
                    }])->limit(5);
            }])->first();
        return response()->json([
            'status' => 'success',
            'post' => $post], 200);
    }

    private function getPosts()
    {
        $user = \Auth::user();
        if ($user->isSuperAdmin()) {
            $posts = Post::orderByDesc('created_at');
        } elseif ($user->isAdmin() && !$user->isSuperAdmin()) {
            $posts = Post::where("faculty_id", $user->faculty_id)
                        ->where("access_level", ">", $user->highestAccessLevel());
        } else {
            $posts = Post::where("faculty_id", $user->faculty_id)
                        ->where("department_id", $user->department_id)
                        ->where("access_level", ">", $user->highestAccessLevel());
        }
        return $posts;
    }

    public function list(Request $request)
    {      
        $date = $request->has("loaded") ? 
                    Carbon::createFromTimestampMs($request->loaded) : 
                    Carbon::now();
        $posts = $this->getPosts()->where("created_at", '<', $date)->orderByDesc('created_at')->paginate(10);

        $date = $request->has("last_loaded") ? 
                    Carbon::createFromTimestampMs($request->last_loaded) : 
                    Carbon::now();
        $new_posts_count = $this->getPosts()->where("created_at", '>=', $date)->count();

        return response()->json([
            'status' => 'success',
            'posts' => $posts,
            'new_posts_count' => $new_posts_count,
        ], 200);
    }

    public function latest(Request $request)
    {
        $current_time = $request->has("last_loaded") ? 
                    Carbon::createFromTimestampMs($request->last_loaded) : 
                    Carbon::now();
        $posts = $this->getPosts()->where("created_at", '>=', $current_time)->orderByDesc('created_at')->get();

        return response()->json([
            'status' => 'success',
            'posts' => $posts,
            'loaded_at' => Carbon::now()->timestamp * 1000
        ], 200);
    }
    public function listWithFilter()
    {
        # code...
    }
}
