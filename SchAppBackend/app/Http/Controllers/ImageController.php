<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Image;
use Validator;

class ImageController extends Controller
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

    static function storeImages($images, $field = null, $field_id = null)
    {
        foreach ($images as $file) {
            $filename = $field . "_" . $field_id . "/" . uniqid() . pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME) ;
            \Storage::disk('local')->put($filename, file_get_contents($file));
            $image = Image::create([
                'path' => $filename,
                $field . '_id' => $field_id
                ]);
        }
    }

    static function store($file, $field = null, $field_id = null)
    {
        $filename = $field . "_" . $field_id . "/" . uniqid() . pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME) ;
        \Storage::disk('local')->put($filename, file_get_contents($file));
        return $filename;
    }

    public function get($folder= null, $reference = null)
    {
        $file = \Storage::disk('local')->get($folder . '/' . $reference);
        $response =  new Response($file, 200);
        return $response->header('Content-Type', 'image/jpeg');
    }
}
