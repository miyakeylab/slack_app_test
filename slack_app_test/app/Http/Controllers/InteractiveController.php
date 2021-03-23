<?php

namespace App\Http\Controllers;

use App\Models\SlackSendEmojiChange;
use App\Notifications\SlackNotification;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class InteractiveController extends Controller
{
    protected $apiKey;
    protected $teamId;
    protected $appId;
    protected $oauthToken;

    public function __construct()
    {
        $this->apiKey = config('slack.setting.api_key');
        $this->oauthToken = config('slack.setting.oauth_token');
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

        $payload = $request->input('payload');
        $postData = json_decode($payload, true);
        $type = $postData['type'];
        $token = $postData['token'];

        if ($token == $this->apiKey ) {

            if ($type == "message_action") {

                Log::info('message_action');
                $message = $postData['message']['text'];
                logger($message);
                $url = 'https://slack.com/api/views.open';
                $token = $this->oauthToken;
                $view = $this->getModalContent($message);
                $trigger_id = $postData['trigger_id'];

                $params = [
                    'view' => json_encode($view),
                    'trigger_id' => $trigger_id
                ];

                $headers = [
                    'Content-type' => 'application/json',
                    'Authorization'  =>  'Bearer ' . $token
                ];

                $client = new Client();
                $response = $client->request(
                    'POST',
                    $url, // URLを設定
                    [
                        'headers' => $headers,
                        'json' => $params
                    ] // パラメーターがあれば設定
                );

                $log = json_decode($response->getBody()->getContents(), true);
                Log::info(print_r($log, true));

                return response('',200);

            } else if ($type == "view_submission") {
                Log::info('view_submission');

                return response('',200);
            } else {
                Log::info('not type: ' . $type);
            }
        } else {
            Log::info('不正アクセストークン');

        }
    }

    /**
     * ダイアログのテンプレートを作る
     *
     * @return array
     */
    function getModalContent ($message) {
        return [
            "type" => "modal",
            "title" => [
                "type" => "plain_text",
                "text" => "Hajimari Wikiにメッセージ保存",
                "emoji" => true
            ],
            "submit" => [
                "type" => "plain_text",
                "text" => "wiki保存",
                "emoji" => true
            ],
            "close" => [
                "type" => "plain_text",
                "text" => "キャンセル",
                "emoji" => true
            ],
            "blocks" => [
                [
                    "type" => "input",
                    "block_id" => "title",
                    "element" => [
                        "type" => "plain_text_input",
                        "action_id" => "wiki_title",
                        "placeholder" => [
                            "type" => "plain_text",
                            "text" => "タイトル"
                        ],
                    ],
                    "label" => [
                        "type" => "plain_text",
                        "text" => "タイトル(任意)"
                    ]
                ],
                [
                    "type" => "input",
                    "block_id" => "description",
                    "element" => [
                        "type" => "plain_text_input",
                        "action_id" => "wiki_description",
                        "multiline" => true,
                        "initial_value"=> $message,
                        "placeholder" => [
                            "type" => "plain_text",
                            "text" => ""
                        ],
                    ],
                    "label" => [
                        "type" => "plain_text",
                        "text" => "本文"
                    ]
                ]
            ]
        ];
    }

}
