<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrganisationContacts extends Model
{
	protected $fillable = [
		'contact_unique_id',
		'organisation_id',
		'is_organisation_agent',
		'organisation_agent_user_id',
		'firstname',
		'lastname',
		'email',
		'company_name',
		'avatar_url',
		'messenger_instances_token',
		'created_from_domain_unique_id',
		'created_by_messenger_instance',
		'is_anonymouse',
		'date_of_birth',
	];
}
