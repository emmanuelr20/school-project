<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use App\Models\Image;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function test($date)
    {
        // $date = Carbon::createFromFormat('Y-m-d\TH:i:s.uO', $date)->format('Y-m-d H:i');

        // $image = new Image();
        // $image->path = 'ghdufy';
        // $image->created_at = Carbon::createFromTimestampMs($date);
        // $image->save();

        // $image = new Image();
        // $image->path = 'ghdufy';
        // $image->created_at = Carbon::createFromTimestampMs("1534934246820");
        // $image->save();

        // $image = new Image();
        // $image->path = 'ghdufy';
        // $image->created_at = Carbon::createFromTimestampMs("1534934247000");
        // $image->save();

        // $images = Image::where('created_at', "<" , Carbon::createFromTimestampMs($date))->get();
        
        return response()->json([ "date" => Carbon::createFromTimestampMs($date)]);
    }
}
