<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmojiReactionHistories extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('emoji_reaction_histories', function (Blueprint $table) {
            $table->increments('id')->comment('id');
            $table->unsignedInteger('source_user_id')->comment('絵文字送ったユーザーid');
            $table->unsignedInteger('destination_user_id')->comment('絵文字受けたユーザーid');
            $table->unsignedInteger('channel_id')->nullable()->comment('channel id');
            $table->char('emoji',255)->comment('絵文字');

            $table->timestamps();

            $table->index('source_user_id', 'emoji_reaction_histories_source_user_id_index');
            $table->index('destination_user_id', 'emoji_reaction_histories_destination_user_id_index');
            $table->index('channel_id', 'emoji_reaction_histories_channel_id_index');
            $table->index('emoji', 'emoji_reaction_histories_emoji_index');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('emoji_reaction_histories');
    }
}
