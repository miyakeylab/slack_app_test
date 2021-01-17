<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class EventController extends Controller
{
    /**
     * @param Request $request
     * @return false|string
     */
    public function index(Request $request)
    {
        logger($request->all());

        return json_encode([]);
    }

}
