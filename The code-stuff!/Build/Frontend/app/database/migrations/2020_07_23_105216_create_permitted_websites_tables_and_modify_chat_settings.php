<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePermittedWebsitesTablesAndModifyChatSettings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('permitted_websites', function (Blueprint $table) {
			$table->bigIncrements('id');
			$table->string('org_id');
			$table->longText('web_host');
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
        Schema::dropIfExists('permitted_websites');
    }
}
