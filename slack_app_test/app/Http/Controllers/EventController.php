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

        if (isset($req['challenge'])) {
            return json_encode([$req['challenge']]);
        } else {
            if(isset($req['type'])&& $req['type'] == "event_callback") {
                Log::info('event_callback');
                if (isset($req['event']['type']) && $req['event']['type'] == "emoji_changed") {
                    if ($req['event']['subtype'] == "add") {
                        $name = $req['event']["name"];
                        $text = "{$name}の絵文字が追加されました！\n\n :{$name}: ";

                    } else if ($req['event']['subtype'] == "remove") {
                        $names = $req['event']["names"];
                        $icons = "";
                        foreach ($names as $name) {
                            $icons .= "\n " . $name;
                        }

                        $text = "絵文字がなくなっちゃいました :cry:\n\n {$icons}";
                    }

                    Log::info('emoji_change');
                }
            }
            return;
        }
    }

}
