<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContactMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contact_messages', function (Blueprint $table) {
			$table->id();
			$table->string('message_unique_id', 128)->unique();
			$table->string('messenger_instances_token', 128)->nullable();
			$table->string('organisation_id', 128);
			$table->string('sender_contact_id', 128)->nullable();
			$table->string('receiver_contact_id', 128)->nullable();
			$table->boolean('sent_by_agent')->default(false);
			$table->boolean('sent_by_bot')->default(false);
			$table->longText('message_text');
			$table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
			$table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP'));
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('contact_messages');
    }
}
