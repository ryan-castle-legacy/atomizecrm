<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Organisations extends Model
{
	protected $fillable = [
        'organisation_unique_id', 'name', 'owner_user_id',
    ];
}
