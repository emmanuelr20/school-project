<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use App\Models\Comment;
use App\Models\Post;
use App\Models\Poll;
use Carbon\Carbon;

class CommentController extends Controller
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

    public function createForPost(Request $request, $post)
    {
        $post = Post::where('id', $post)->firstOrFail();
        $validator = Validator::make($request->all(), [
            'comment' => 'required'
        ]);

        if($validator->fails()){
            return response()->json([
                'status' => 'error',
                'data' => $validator->errors()], 200);
        }

        $comment = Comment::create([
            'body' => $request->comment,
            'post_id' => $post->id,
            'user_id' => \Auth::user()->id
        ]);

        $comment = Comment::where('id', $comment->id)->with('user')->first();
        return response()->json([
            'status' => 'success',
            'message' => 'comment successfully posted',
            'comment' => $comment], 200);
    }

    public function createForPoll(Request $request, $poll)
    {
        $poll = Poll::where('id', $poll)->firstOrFail();
        $validator = Validator::make($request->all(), [
            'comment' => 'required'
        ]);

        if($validator->fails()){
            return response()->json([
                'status' => 'error',
                'data' => $validator->errors()], 200);
        }

        $comment = Comment::create([
            'body' => $request->comment,
            'poll_id' => $poll->id,
            'user_id' => \Auth::user()->id
        ]);

        $comment = Comment::where('id', $comment->id)->with('user')->first();
        return response()->json([
            'status' => 'success',
            'message' => 'comment successfully posted',
            'comment' => $comment], 200);
    }

    public function delete(Comment $comment)
    {
        if (\Auth::user()->id == $comment->user->id || \Auth::user()->isAdmin()) {
            $comment->delete();
            return response()->json([
                'status' => 'success',
                'message' => 'Comment deleted successfully.']);
        } else {
            return response()->json([
                'status' => 'error',
                'data' => 'Authorised attempt.']);
        }
    }

    public function listPost(Request $request, $post)
    {
        $post = Post::where('id', $post)->firstOrFail();
       
        $date = $request->has("loaded") ? 
            Carbon::createFromTimestampMs($request->loaded) : 
            Carbon::now();
        $comments = Comment::where([["post_id", $post->id], ["created_at", '<', $date]])->orderByDesc('created_at')->paginate(5);

        return response()->json([
            'status' => 'success',
            'comments' => $comments
        ], 200);
    }

    public function listPoll(Request $request, $poll)
    {
        $poll = Poll::where('id', $poll)->firstOrFail();
       
        $date = $request->has("loaded") ? 
            Carbon::createFromTimestampMs($request->loaded) : 
            Carbon::now();
        $comments = Comment::where([["poll_id", $poll->id], ["created_at", '<', $date]])->orderByDesc('created_at')->paginate(5);

        return response()->json([
            'status' => 'success',
            'comments' => $comments
        ], 200);
    }
}
