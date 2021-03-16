<?php
namespace App\Models;

use Illuminate\Notifications\Notifiable;

class SlackSendEmojiChange
{
    use Notifiable;

    public function routeNotificationForSlack()
    {
        return config('slack.emoji_change');
    }
}
