<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use App\User;
use App\Model\Notification;

class UserController extends Controller
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
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email|unique:users',
            'staff_id' => 'required|unique:users',
            'image' => 'mimes:jpg,jpeg,png,gif',
            'password' => 'required|confirmed|min:8',
            'department_id' => 'required'
        ]);

        if($validator->fails())
            return response()->json(['status' => 'error', 'message' => $validator->errors()], 401);

        $user = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'telephone' => $request->has('telephone') ? $request->telephone : null,
            'staff_id' => $request->staff_id,
            'password' => app('hash')->make($request->password),
            'middle_name' => $request->has('middle_name') ? $request->middle_name : null,
            'department_id' => $request->has('department_id') ? $request->department_id : null,
            'is_academic' => $request->has('is_academic') ? $request->is_academic : true
        ]);
        
        return response()->json(['status' => 'success', 'user' => $user], 200);
    }

    public function notify($message, $user)
    {
        return Notification::create([
            'message' => $message,
            'user_id' => $user,
            'sender_id' => \Auth::user()->id
        ]);
    }

    public function delete($user)
    {
        if(\Auth::user()->isSuperAdmin()){
            $user = User::where('id', $user)->first();
            if ($user->isSuperAdmin()){
                return response()->json([
                    'status' => 'error',
                    'message' => 'Unauthorized: user is super Admin'
                ], 401); 
            }
            $user->delete();
            return response()->json([
                'status' => 'success',
                'message' => 'user successfully deleted'
            ], 201);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized'
            ], 401);
        }
    }

    public function update(Request $request)
    {
        # code...
    }

    public function setSupendAndNotify($user)
    {
        $user->is_suspended = true;
        $message = 'You were suspended on '. Carbon::now() . ' by ' . \Auth::user()->staff_id . ' ('. \Auth::user()->getFullName() . ') ';
        $this->notify($message, $user);
        $user->save();
        return response()->json([
            'status' => 'success',
            'message' => 'user successfully suspended'
        ], 201);
    }
    public function suspend($user)
    {
        if(\Auth::user()->isSuperAdmin()){
            $user = User::where('id', $user)->first();
            if ($user->isSuperAdmin()){
                return response()->json([
                    'status' => 'error',
                    'message' => 'Unauthorized: user is super Admin'
                ], 401); 
            }
            return $this->setSupendAndNotify($user);
        } elseif (\Auth::user()->isDean()) {
            $user = User::where('id', $user)->first();
            if ($user->faculty_id != \Auth::user()->faculty_id || $user->isSuperAdmin()){
                return response()->json([
                    'status' => 'error',
                    'message' => 'Unauthorized'
                ], 401); 
            }
            return $this->setSupendAndNotify($user);
        } elseif (\Auth::user()->isAdmin()) {
            $user = User::where('id', $user)->first();
            if ($user->department_id != \Auth::user()->department_id || $user->isSuperAdmin()){
                return response()->json([
                    'status' => 'error',
                    'message' => 'Unauthorized'
                ], 401); 
            }
            return $this->setSupendAndNotify($user);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized'
            ], 401);
        }
    }

    public function setUnSupendAndNotify($user)
    {
        $user->is_suspended = false;
        $message = 'You were unsuspended on '. Carbon::now() . ' by ' . \Auth::user()->staff_id . ' ('. \Auth::user()->getFullName() . ') ';
        $this->notify($message, $user);
        $user->save();
        return response()->json([
            'status' => 'success',
            'message' => 'user successfully suspended'
        ], 201);
    }
    public function unsuspend($user)
    {
        if(\Auth::user()->isSuperAdmin()){
            $user = User::where('id', $user)->first();
            if ($user->isSuperAdmin()){
                return response()->json([
                    'status' => 'error',
                    'message' => 'Unauthorized: user is super Admin'
                ], 401); 
            }
            return $this->setUnSupendAndNotify($user);
        } elseif (\Auth::user()->isDean()) {
            $user = User::where('id', $user)->first();
            if ($user->faculty_id != \Auth::user()->faculty_id || $user->isSuperAdmin()){
                return response()->json([
                    'status' => 'error',
                    'message' => 'Unauthorized'
                ], 401); 
            }
            return $this->setUnSupendAndNotify($user);
        } elseif (\Auth::user()->isAdmin()) {
            $user = User::where('id', $user)->first();
            if ($user->department_id != \Auth::user()->department_id || $user->isSuperAdmin()){
                return response()->json([
                    'status' => 'error',
                    'message' => 'Unauthorized'
                ], 401); 
            }
            return $this->setUnSupendAndNotify($user);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized'
            ], 401);
        }
    }


    public function makeAdmin($user)
    {
        $admin = Role::where('name', 'admin')->first();
        $user = User::where('id', $user)->firstOrFail();
        $user->roles()->attach($admin);
        $message = 'You were made an administrator on '. Carbon::now() . ' by ' . \Auth::user()->staff_id . ' ('. \Auth::user()->getFullName() . ') ';
        $this->notify($message, $user);
        $user->save();
        return response()->json([
            'status' => 'success',
            'message' => 'Role added successfully!'
        ], 200);  
    }

    public function revokeAdmin($user)
    {
        $admin = Role::where('name', 'admin')->get();
        $user = User::where('id', $user)->firstOrFail();
        $user->roles()->detach($admin);
        $message = 'You administrator privileges were revoke on '. Carbon::now() . ' by ' . \Auth::user()->staff_id . ' ('. \Auth::user()->getFullName() . ') ';
        $this->notify($message, $user);
        $user->save();
        return response()->json([
            'status' => 'success',
            'message' => 'Role added successfully!'
        ], 200);  
    }

    public function makeHOD($user)
    {
        $user = User::where('id', $user)->firstOrFail();
        $department = $user->department;
        if(!$department) {
            return response()->json([
                'status' => 'error',
                'message' => 'Department Not Found!'
            ], 404);
        }
        $department->head()->associate($user);
        return response()->json([
            'status' => 'success',
            'message' => 'Role added successfully!'
        ], 200);
    }

    public function makeDean($user)
    {
        $user = User::where('id', $user)->firstOrFail();
        $department = $user->department;
        $faculty = $department ? $department->faculty: null;
        if(!$faculty) {
            return response()->json([
                'status' => 'error',
                'message' => 'Faculty Not Found!'
            ], 404);
        }
        $faculty->dean()->associate($user);
        return response()->json([
            'status' => 'success',
            'message' => 'Role added successfully!'
        ], 200);
    }
}
