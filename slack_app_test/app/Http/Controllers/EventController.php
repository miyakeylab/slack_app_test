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
        $this->teamId = config('slack.setting.team_id');
        $this->appId = config('slack.setting.app_id');
    }

    /**
     * @param Request $request
     * @return false|string
     */
    public function index(Request $request)
    {
        Log::info(__CLASS__ . __FUNCTION__);
        $req = $request->all();
        logger($req);

        $type = $request->input('type');

        if ($type == "url_verification") {
            return json_encode([$req['challenge']]);
        } else {
            $token = $request->input('token');
            $team = $request->input('team_id');
            $app= $request->input('api_app_id');
            if ($token == $this->apiKey &&
                $team == $this->teamId &&
                $app == $this->appId)
            {
                $event = $request->input('event');

                if ($type == "event_callback") {
                    Log::info('event_callback');
                    if (isset($event['type']) && $event['type'] == "emoji_changed") {
                        if ($event['subtype'] == "add") {
                            $name = $event["name"];
                            $text = "ぼんぬさん！{$name}の絵文字が追加されました！\n\n :{$name}: ";
                            $sendSlack = new SlackSendEmojiChange();
                            $sendSlack->notify(new SlackNotification($text));

                        } else if ($event['subtype'] == "remove") {
                            $names = $event["names"];
                            $icons = "";
                            foreach ($names as $name) {
                                $icons .= "\n " . $name;
                            }

                            $text = "ぼんぬさん！絵文字がなくなっちゃいました :cry: {$icons}";
                            $sendSlack = new SlackSendEmojiChange();
                            $sendSlack->notify(new SlackNotification($text));
                        }else{
                            Log::info('not subtype ' . $event['subtype'] );

                        }

                        Log::info('emoji_change');
                    }
                }else{

                    Log::info('not event_callback');
                }
            }else{

                Log::info('不正アクセストークン');

            }
        }
    }

}
