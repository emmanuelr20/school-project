<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Poll;
use App\Models\Option;
use Validator;
use Carbon\Carbon;

class PollController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        // $this->middleware('admin')->only(['create', 'delete']);
    }

    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'body' => 'required',
            'image' => 'mimes:jpg,jpeg,png,gif',
            'options.*' => 'required'
        ]);

        if($validator->fails())
            return response()->json(['status' => 'error', 'message' => $validator->errors()], 401);

        $poll = Poll::create([
            'title' => $request->title,
            'body' => $request->body,
            'access_level' => $request->has('access_level') ? $request->access_level : null,
            'department_id' => $request->has('department_id') ? $request->department_id : null,
            'faculty_id' => $request->has('faculty_id') ? $request->faculty_id : null,
            'role_id' => $request->has('role_id') ? $request->role_id : null,
            'user_id' => \Auth::user()->id,
        ]);

        foreach ($request->options as $option) {
            Option::create([
                'body' => $option,
                'poll_id' => $poll->id
            ]);
        }

        if( !empty($request->file('image')) ) {
            $poll->image_url = ImageController::store($request->file('image'), 'poll', $poll->id);
            $poll->save();
        }
        
        return response()->json(['status' => 'success', 'poll' => $poll], 200);
    }

    public function delete(Poll $poll)
    {
        $poll = Poll::where('id', $id)->first();
        if (!auth()->user()->id == $poll->user->id || auth()->user()->isSuperAdmin()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized'
            ], 401);
        }
        $poll->delete();
        return response()->json([
            'status' => 'success'], 200); 
    }

    public function get($id)
    {
        $poll = Poll::where('id', $id)->with([
            'user'=> function($q){
                $q->select([
                    'id', 'first_name', 'last_name', 'email', 'avatar_url', 'staff_id'
                ]);
            }, 'faculty', 'department', 
            'comments'=> function($q){
                return $q->with(['user'=> function($q){
                    $q->select([
                        'id', 'first_name', 'last_name', 'email', 'avatar_url', 'staff_id'
                    ]);
                }]);
            }, 
            'options' => function($q){
                return $q->with('votes');
            }
            ])->first();
        if($poll){
            $poll->total_votes = $poll->totalVotes();
            return response()->json([
                'status' => 'success',
                'poll' => $poll], 200);
        } else {
            return response()->json([
                'status' => 'failed'], 404);
        }
    }

    private function getPolls()
    {
        $user = \Auth::user();
        if ($user->isSuperAdmin()) {
            $polls = Poll::orderByDesc('created_at');
        } elseif ($user->isAdmin() && !$user->isSuperAdmin()) {
            $polls = Poll::whereIn("faculty_id", [$user->faculty_id, null])
                        ->where("access_level", ">", $user->highestAccessLevel());
        } else {
            $polls = Poll::whereIn("faculty_id", [$user->faculty_id, null])
                        ->whereIn("department_id", [$user->department_id, null])
                        ->where("access_level", ">", $user->highestAccessLevel());
        }

        return $polls;
    }
    
    public function list(Request $request)
    {
        $date = $request->has("loaded") ? 
                Carbon::createFromTimestampMs($request->loaded) : 
                Carbon::now();
        $polls = $this->getpolls()->where("created_at", '<', $date)->orderByDesc('created_at')->paginate(10);

        $date = $request->has("last_loaded") ? 
                Carbon::createFromTimestampMs($request->last_loaded) : 
                Carbon::now();
        $new_polls_count = $this->getPolls()->where("created_at", '>=', $date)->count();

        return response()->json([
            'status' => 'success',
            'polls' => $polls, 
            'new_polls_count' => $new_polls_count,], 200);
    }

    public function latest(Request $request)
    {
        $current_time = $request->has("last_loaded") ? 
                    Carbon::createFromTimestampMs($request->last_loaded) : 
                    Carbon::now();
        $polls = $this->getPolls()->where("created_at", '>=', $current_time)->get();

        return response()->json([
            'status' => 'success',
            'polls' => $polls,
            'loaded_at' => Carbon::now()->timestamp * 1000
        ], 200);
    }

    public function listWithFilter($value='')
    {
        # code...
    }
}
