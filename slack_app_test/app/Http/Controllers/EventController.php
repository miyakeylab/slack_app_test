<?php

namespace App\Http\Controllers;

use App\Models\SlackSendEmojiChange;
use App\Notifications\SlackNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class EventController extends Controller
{
    protected $apiKey;
    protected $teamId;
    protected $appId;

    public function __construct()
    {
        $this->apiKey = config('slack.setting.api_key');
        $this->teamId = config('slack.setting.team_id');;
        $this->appId = config('slack.setting.app_id');;
    }

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
            if ($req['token'] == $this->apiKey &&
                $req['team_id'] == $this->teamId &&
                $req['api_app_id'] == $this->appId) {

                if (isset($req['type']) && $req['type'] == "event_callback") {
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
                        }else{
                            Log::info('not subtype ' . $req['event']['subtype'] );

                        }

                        Log::info('emoji_change');
                    }
                }else{

                    Log::info('not event_callback');
                }
            }else{

                Log::info('不正アクセストークン');

                Log::info($req['token'] );
                Log::info($this->apiKey);

                Log::info($req['team_id'] );
                Log::info($this->teamId);

                Log::info($req['api_app_id'] );
                Log::info($this->appId);
            }
        }
    }

}
