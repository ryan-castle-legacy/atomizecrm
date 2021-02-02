<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MessengerInstances extends Model
{
	protected $fillable = [
		'instance_token', 'contact_id', 'organisation_id', 'assigned_agent_id',
	];
}
