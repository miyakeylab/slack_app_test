<?php

namespace App\Http\Controllers;

use App\Models\SlackSendEmojiChange;
use App\Notifications\SlackNotification;
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
                        $text = "ぼんぬさん！{$name}の絵文字が追加されました！\n\n :{$name}: ";
                        $sendSlack = new SlackSendEmojiChange();
                        $sendSlack->notify(new SlackNotification($text));

                    } else if ($req['event']['subtype'] == "remove") {
                        $names = $req['event']["names"];
                        $icons = "";
                        foreach ($names as $name) {
                            $icons .= "\n " . $name;
                        }

                        $text = "ぼんぬさん！絵文字がなくなっちゃいました :cry:\n\n {$icons}";
                        $sendSlack = new SlackSendEmojiChange();
                        $sendSlack->notify(new SlackNotification($text));
                    }

                    Log::info('emoji_change');
                }
            }
            return;
        }
    }

}
