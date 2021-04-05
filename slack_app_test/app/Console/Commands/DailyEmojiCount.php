<?php
namespace App\Console\Commands;

use App\Models\EmojiReactionHistory;
use Illuminate\Console\Command;
use Carbon\Carbon;
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

       logger($emoji);

    }
}
