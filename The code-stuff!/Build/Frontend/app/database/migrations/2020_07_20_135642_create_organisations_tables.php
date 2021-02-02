<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrganisationsTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
		Schema::create('organisations', function (Blueprint $table) {
            $table->bigIncrements('id');
			$table->string('name');
			$table->string('token')->unique();
			$table->dateTime('date_created');
			$table->dateTime('date_updated')->nullable();
        });

		Schema::create('organisations_agents', function (Blueprint $table) {
			$table->integer('user_id');
			$table->integer('org_id');
			$table->boolean('is_admin')->default(false);
			$table->dateTime('date_created');
			$table->dateTime('date_updated')->nullable();
        });

		$org = DB::table('organisations')->insertGetId([
			'name'				=> 'Atomize',
			'token'				=> 'atomize',
			'date_created'		=> date('Y-m-d H:i:s', time()),
			'date_updated'		=> date('Y-m-d H:i:s', time()),
		]);

		DB::table('organisations_agents')->insert([
			'user_id'			=> 1,
			'org_id'			=> $org,
			'is_admin'			=> true,
			'date_created'		=> date('Y-m-d H:i:s', time()),
			'date_updated'		=> date('Y-m-d H:i:s', time()),
		]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
		Schema::dropIfExists('organisations');
        Schema::dropIfExists('organisations_agents');
    }
}
