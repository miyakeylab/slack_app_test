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

        $start = Carbon::now()->subDay()->startofday();
        $end = Carbon::now()->startOfDay();

        $emoji = EmojiReactionHistory::select(DB::raw('count(id) as cnt, emoji'))
            ->where('created_at', '>=', $start)
            ->where('created_at', '<', $end)
            ->groupBy('emoji')
            ->orderByDesc('cnt')
            ->get();
        $emojiCount = EmojiReactionHistory::select(DB::raw('count(id) as cnt, emoji'))
            ->where('created_at', '>=', $start)
            ->where('created_at', '<', $end)
            ->count();


        $no = 1;
        $cnt = 1;
        $preCnt = 0;
        $text = " *昨日のスタンプランキング* \n";
        $text .= " ({$start->format('Y/m/d H:i')}〜{$end->format('Y/m/d H:i')}) \n";
        $text .= " 総スタンプ数{$emojiCount}回 \n";
        foreach ($emoji as $e)
        {
            if($cnt > 20){
                break;
            }

            // 同率では無い場合で前回値がマイナスで無い場合はNoはカウント数と同じ
            if($preCnt != $e['cnt'] )
            {
                $no = $cnt;
            }

            $text .= "第{$no}位 {$e['cnt']}回　　:{$e['emoji']}: \n";

            $preCnt = $e['cnt'];
            $cnt++;
        }

        logger($text);

        $sendSlack = new SlackSendEmojiChange();
        $sendSlack->notify(new SlackNotification($text));
    }
}
