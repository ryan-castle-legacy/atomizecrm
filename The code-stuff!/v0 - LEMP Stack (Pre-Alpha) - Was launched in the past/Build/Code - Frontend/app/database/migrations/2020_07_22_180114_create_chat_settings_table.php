<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChatSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('widget_chat_settings', function (Blueprint $table) {
			$table->bigIncrements('id');
			$table->integer('org_id');
			$table->dateTime('date_created');
			$table->dateTime('date_updated')->nullable();

			$table->boolean('startChatWithAI')->default(false);
			$table->longText('startChatWithAI_message')->nullable();

			$table->longText('outOfOffice_times')->nullable();
			$table->longText('outOfOffice_message')->nullable();
			$table->longText('outOfOffice_requestEmail')->nullable();
			$table->longText('outOfOffice_requestDetails')->nullable();

			$table->string('widget_primaryColour')->nullable();
			$table->string('widget_primaryAlt')->nullable();

			$table->longText('widget_logoSRC')->nullable();
			$table->longText('widget_greeting')->nullable();
			$table->longText('widget_description')->nullable();

			$table->longText('widget_iconSRC')->nullable();
			$table->longText('widget_iconAlt')->nullable();
			$table->longText('widget_teamName')->nullable();
            $table->longText('widget_teamDescription')->nullable();

			$table->longText('supportEmailAddress')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('widget_chat_settings');
    }
}
