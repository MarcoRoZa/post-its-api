<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class WebController extends Controller
{
    public function image(Request $request)
    {
        return response()->file(storage_path("app/" . $request->path()));
    }
}
