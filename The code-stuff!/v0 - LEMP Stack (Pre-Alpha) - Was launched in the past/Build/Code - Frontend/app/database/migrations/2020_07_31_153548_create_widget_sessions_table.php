<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWidgetSessionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
		Schema::create('widget_instances', function (Blueprint $table) {
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
        Schema::dropIfExists('widget_instances');
    }
}
