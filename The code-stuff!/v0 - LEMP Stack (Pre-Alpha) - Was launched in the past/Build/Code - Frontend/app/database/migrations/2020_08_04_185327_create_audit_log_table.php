<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAuditLogTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('audit_log', function (Blueprint $table) {
			$table->bigIncrements('id');
			$table->integer('actioner_user_id');
			$table->integer('alt_user_id')->nullable();
			$table->string('type');
			$table->longText('meta');
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
        Schema::dropIfExists('audit_log');
    }
}
