<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;

class SuperAdminController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middelware('super_admin')
    }

    //
}
