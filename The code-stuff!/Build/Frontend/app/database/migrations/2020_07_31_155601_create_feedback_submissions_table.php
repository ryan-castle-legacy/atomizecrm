<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFeedbackSubmissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('feedback_submissions', function (Blueprint $table) {
			$table->bigIncrements('id');
			$table->integer('client_id')->nullable();
			$table->integer('org_id');
			$table->string('type');
			$table->longText('feedback');
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
        Schema::dropIfExists('feedback_submissions');
    }
}
