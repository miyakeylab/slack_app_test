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
    protected $accessToken;

    public function __construct()
    {
        $this->apiKey = config('slack.setting.api_key');
        $this->teamId = config('slack.setting.team_id');
        $this->accessToken = config('slack.setting.access_token');
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
        $team = $postData['team_id'];

        if ($token == $this->apiKey &&
            $team == $this->teamId ) {

            if ($type == "message_action") {
                Log::info('message_action');
                $url = 'https://slack.com/api/views.open';
                $token = $this->accessToken;
                $view = $this->getModalContent();
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
    function getModalContent () {
        return [
            "type" => "modal",
            "title" => [
                "type" => "plain_text",
                "text" => "メンバー登録",
                "emoji" => true
            ],
            "submit" => [
                "type" => "plain_text",
                "text" => "登録",
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
                    "block_id" => "name",
                    "element" => [
                        "type" => "plain_text_input",
                        "action_id" => "氏名",
                        "placeholder" => [
                            "type" => "plain_text",
                            "text" => "田中 太郎"
                        ],
                    ],
                    "label" => [
                        "type" => "plain_text",
                        "text" => "氏名"
                    ]
                ],
                [
                    "type" => "input",
                    "block_id" => "mail",
                    "element" => [
                        "type" => "plain_text_input",
                        "action_id" => "メールアドレス",
                        "placeholder" => [
                            "type" => "plain_text",
                            "text" => "xxx@gmail.com"
                        ],
                    ],
                    "label" => [
                        "type" => "plain_text",
                        "text" => "メールアドレス"
                    ]
                ],
                [
                    "type" => "input",
                    "block_id" => "language",
                    "optional" => true,
                    "element" => [
                        "type" => "checkboxes",
                        "action_id" => "得意言語",
                        "options" => [
                            [
                                "text" => [
                                    "type" => "plain_text",
                                    "text" => "PHP",
                                    "emoji" => true
                                ],
                                "value" => "value-0"
                            ],
                            [
                                "text" => [
                                    "type" => "plain_text",
                                    "text" => "Ruby",
                                    "emoji" => true
                                ],
                                "value" => "value-1"
                            ],
                            [
                                "text" => [
                                    "type" => "plain_text",
                                    "text" => "Javascript/Node.js",
                                    "emoji" => true
                                ],
                                "value" => "value-2"
                            ],
                            [
                                "text" => [
                                    "type" => "plain_text",
                                    "text" => "Python",
                                    "emoji" => true
                                ],
                                "value" => "value-3"
                            ]
                        ]
                    ],
                    "label" => [
                        "type" => "plain_text",
                        "text" => "得意言語",
                        "emoji" => true
                    ]
                ]
            ]
        ];
    }

}
