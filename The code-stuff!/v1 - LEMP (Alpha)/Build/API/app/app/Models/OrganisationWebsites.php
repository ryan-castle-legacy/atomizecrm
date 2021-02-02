<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrganisationWebsites extends Model
{
	protected $fillable = [
        'domain_unique_id', 'organisation_id', 'domain_name', 'owner_user_id',
    ];
}
