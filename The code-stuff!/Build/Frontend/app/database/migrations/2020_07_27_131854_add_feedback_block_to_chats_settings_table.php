<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFeedbackBlockToChatsSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('widget_chat_settings', function (Blueprint $table) {
            $table->boolean('widget_feedbackWidget')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('widget_chat_settings', function (Blueprint $table) {
			$table->dropColumn('widget_feedbackWidget');
        });
    }
}
