<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAddAgentColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
		Schema::table('chat_messages', function (Blueprint $table) {
            $table->integer('agent_id')->nullable();
        });

		Schema::table('chat_instances', function (Blueprint $table) {
			$table->integer('assigned_agent')->nullable();
        });
    }
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
		Schema::table('chat_messages', function (Blueprint $table) {
            $table->dropColumn('agent_id');
        });

		Schema::table('chat_instances', function (Blueprint $table) {
			$table->dropColumn('assigned_agent');
        });
    }
}
