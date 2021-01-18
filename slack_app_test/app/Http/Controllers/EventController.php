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
        Log::info(__CLASS__ . __FUNCTION__);
        $req = $request->all();
        Log::info($req);

        if(isset($req['challenge'])) {
            return json_encode([$req['challenge']]);
        }else{
            return;
        }
    }

}
