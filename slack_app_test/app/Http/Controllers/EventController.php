<?php

namespace App\Http\Controllers;

use App\Models\EmojiReactionHistory;
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
            $app = $request->input('api_app_id');
            if ($token == $this->apiKey &&
                $team == $this->teamId &&
                $app == $this->appId) {
                $event = $request->input('event');

                if ($type == "event_callback") {
                    Log::info('event_callback');
                    if (isset($event['type']) && $event['type'] == "emoji_changed") {
                        if ($event['subtype'] == "add") {
                            $name = $event["name"];
                            $text = "ぼんぬさん！{$name}の絵文字が追加されました！";
                            $text2 = ":{$name}:";
                            $sendSlack = new SlackSendEmojiChange();
                            $sendSlack->notify(new SlackNotification($text));
                            $sendSlack->notify(new SlackNotification($text2));

                        } else if ($event['subtype'] == "remove") {
                            $names = $event["names"];
                            $icons = "";
                            foreach ($names as $name) {
                                $icons .= "\n " . $name;
                            }

                            $text = "ぼんぬさん！絵文字がなくなっちゃいました :cry: {$icons}";
                            $sendSlack = new SlackSendEmojiChange();
                            $sendSlack->notify(new SlackNotification($text));
                        } else {
                            Log::info('not subtype ' . $event['subtype']);

                        }

                        Log::info('emoji_change');
                    } else if (isset($event['type']) && $event['type'] == "reaction_added") {
                        Log::info('reaction_added');

                        $emoji = array(
                            'source_user_id' => $event['user'],
                            'emoji' => $event['reaction']);

                        if (isset($item['item_user'])) {
                            $emoji['destination_user_id'] = $event['item_user'];
                        }
                        $item = $event['item'];

                        if ($item['type'] == "message" && isset($item['channel'])) {
                            $emoji['channel_id'] = $item['channel'];
                        }

                        // 保存
                        EmojiReactionHistory::create($emoji);
                        Log::info($event);
                    }

                } else {

                    Log::info('not event_callback');
                }
            } else {

                Log::info('不正アクセストークン');

            }
        }
    }

}
