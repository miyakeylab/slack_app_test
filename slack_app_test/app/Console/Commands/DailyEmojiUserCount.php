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
class DailyEmojiUserCount extends Command
{
    protected $signature = 'DailyEmojiUserCount';
    protected $description = '日々の利用絵文字数ユーザー数を計測';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {

        $start = Carbon::now()->subDay()->startofday();
        $end = Carbon::now()->startOfDay();

        $emoji = EmojiReactionHistory::select(DB::raw('count(id) as cnt, source_user_id'))
            ->where('created_at', '>=', $start)
            ->where('created_at', '<', $end)
            ->groupBy('source_user_id')
            ->orderByDesc('cnt')
            ->get();

        $no = 1;
        $text = " *昨日のスタンパーランキング* \n";
        $text .= " ({$start->format('Y/m/d H:i')}〜{$end->format('Y/m/d H:i')}) \n";
        foreach ($emoji as $e)
        {
            if($no > 10){
                break;
            }
            
            if($no == 1){
                $text .= "第{$no}位 {$e['cnt']}回 :tada: <@{$e['source_user_id']}> :tada: \n";

            }else {
                $text .= "第{$no}位 {$e['cnt']}回　<@{$e['source_user_id']}> \n";
            }
            $no++;
        }

        logger($text);

        $sendSlack = new SlackSendEmojiChange();
        $sendSlack->notify(new SlackNotification($text));
    }
}
