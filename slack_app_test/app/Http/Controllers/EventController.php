<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class EventController extends Controller
{
    /**
     * @param Request $request
     * @return false|string
     */
    public function index(Request $request)
    {
//        logger(__CLASS__ . __FUNCTION__);
//        logger($request->all());
        Log::info(__CLASS__ . __FUNCTION__);
        return json_encode([]);
    }

}
