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
class DailyEmojiCount extends Command
{
    protected $signature = 'DailyEmojiCount';
    protected $description = '日々の利用絵文字数を計測';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {

        $now = Carbon::now();
        $start = $now->startofday();
        $emoji = EmojiReactionHistory::select(DB::raw('count(id) as cnt, emoji'))
            ->where('created_at', '>=', $start)
            ->groupBy('emoji')
            ->orderByDesc('cnt')
            ->get();
        $no = 1;
        $text = " *今日の絵文字ランキング* \n";
        foreach ($emoji as $e)
        {
            if($no > 10){
                break;
            }
            $text .= "第{$no}位 {$e['cnt']}回　　:{$e['emoji']}: \n";


            $no++;
        }

        logger($text);

        $sendSlack = new SlackSendEmojiChange();
        $sendSlack->notify(new SlackNotification($text));
    }
}
