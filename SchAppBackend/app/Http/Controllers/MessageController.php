<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use App\Models\Message;
use App\User;

class MessageController extends Controller
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

    public function send(Request $request, $user)
    {
        $validator = Validator::make($request->all(), [
            'body' => 'required'
        ]);
        
        if($validator->fails()){
            return response()->json([
                'status' => 'error',
                'data' => $validator->errors()], 200);
        }

        $message = Message::create([
            'body' => $request->body,
            'receiver_id' => $user,
            'sender_id' => \Auth::user()->id
        ]);

        return response()->json([ 'status' => 'success', 'data' => $message], 200);
    }

    public function delete(Message $message)
    {
        if ($message->user->id == auth()->user()->id) {
            $message->delete();
            return response()->json([ 'status' => 'success'], 200);
        } else {
            return response()->json([ 'status' => 'error'], 200);
        }
        
    }
    
    public function list(User $user)
    {
        $messages = Message::where([['sender_id', '=', auth()->user->id ], 
                                    ['receiver_id', '=', $user->id]])
                            ->orWhere([['sender_id', '=', $user->id], 
                                ['receiver_id', '=', auth()->user->id ]])
                            ->orderByDesc('created_at')->get();
        return response()->json([ 'status' => 'success', 'data' => $messages], 200);
    }
}
