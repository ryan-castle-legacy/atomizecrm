<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDetailsToOrganisationContactsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
		Schema::table('organisation_contacts', function (Blueprint $table) {
			$table->string('firstname', 128)->nullable();
			$table->string('lastname', 128)->nullable();
			$table->string('email', 128)->nullable();
			$table->string('avatar_url', 1024)->nullable();
			$table->string('messenger_instances_token', 128)->nullable();
			$table->string('created_from_domain_unique_id', 128)->nullable();
			$table->boolean('created_by_messenger_instance')->default(false);
			$table->boolean('is_anonymouse')->default(false);
			$table->date('date_of_birth')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('organisation_contacts', function (Blueprint $table) {
			$table->dropColumn('firstname');
			$table->dropColumn('lastname');
			$table->dropColumn('email');
			$table->dropColumn('avatar_url');
			$table->dropColumn('messenger_instances_token');
			$table->dropColumn('created_from_domain_unique_id');
			$table->dropColumn('created_by_messenger_instance');
			$table->dropColumn('is_anonymouse');
			$table->dropColumn('date_of_birth');
        });
    }
}
