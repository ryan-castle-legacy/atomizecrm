<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactMessages extends Model
{
	protected $fillable = [
		'message_unique_id',
		'messenger_instances_token',
		'organisation_id',
		'sender_contact_id',
		'receiver_contact_id',
		'sent_by_agent',
		'sent_by_bot',
		'message_text',
	];
}
