<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\PersonalAccessToken as SanctumPersonalAccessToken;

class PersonalAccessToken extends SanctumPersonalAccessToken
{
    use HasFactory, Uuid;

    protected $table = 'personal_access_tokens';

    public function tokenable()
    {
        return $this->morphTo('tokenable', "tokenable_type", "tokenable_id", "id");
    }
}
