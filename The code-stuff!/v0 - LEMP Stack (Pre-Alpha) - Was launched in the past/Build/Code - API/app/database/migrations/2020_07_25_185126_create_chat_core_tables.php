<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChatCoreTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
		Schema::create('organisations_clients', function (Blueprint $table) {
            $table->bigIncrements('id');
			$table->string('token');
			$table->integer('org_id');
			$table->dateTime('date_created');
			$table->dateTime('date_updated')->nullable();
			$table->string('firstname')->nullable();
			$table->string('lastname')->nullable();
			$table->string('gender')->nullable();
			$table->integer('age')->nullable();
        });

		Schema::create('chat_messages', function (Blueprint $table) {
			$table->bigIncrements('id');
			$table->string('instance_token');
			$table->string('from');
			$table->longText('message');
			$table->dateTime('date_created');
			$table->dateTime('date_updated')->nullable();
        });

		Schema::create('chat_instances', function (Blueprint $table) {
			$table->bigIncrements('id');
			$table->string('token')->unique();
			$table->string('encrypt')->unique();
			$table->integer('client_id')->nullable();
			$table->integer('org_id');
			$table->dateTime('date_created');
			$table->dateTime('date_updated')->nullable();
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
		Schema::dropIfExists('organisations_clients');
		Schema::dropIfExists('chat_messages');
        Schema::dropIfExists('chat_instances');
    }
}
