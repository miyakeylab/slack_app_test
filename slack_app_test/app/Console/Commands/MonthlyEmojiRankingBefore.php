<?php

namespace App\Console\Commands;

use App\Models\EmojiReactionHistory;
use App\Models\SlackSendEmojiChange;
use App\Notifications\SlackNotification;
use Illuminate\Console\Command;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * 日々の利用絵文字数を計測
 *
 */
class MonthlyEmojiRankingBefore extends Command
{
    protected $signature = 'MonthlyEmojiRankingBefore';
    protected $description = '直前告知';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {

        $text = "<!channel> \n" ." *もうまもなく！！！！！！* \n";
        $text .= " *月間スタンパーランキング、月間スタンプランキングの発表がもうまもなく！！！* \n";
        $text .= " \n \n \n \n \n \n 震えて待て！ \n";

        $sendSlack = new SlackSendEmojiChange();
        $sendSlack->notify(new SlackNotification($text));
    }
}
