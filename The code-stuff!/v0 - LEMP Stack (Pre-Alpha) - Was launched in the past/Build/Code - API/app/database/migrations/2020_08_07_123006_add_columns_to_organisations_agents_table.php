<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToOrganisationsAgentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('organisations_agents', function (Blueprint $table) {
			$table->string('name')->nullable();
			$table->string('role')->nullable();
			$table->longText('iconSRC')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('organisations_agents', function (Blueprint $table) {
			$table->dropColumn('name');
			$table->dropColumn('role');
			$table->dropColumn('iconSRC');
        });
    }
}
