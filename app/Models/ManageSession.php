<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class ManageSession extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'details',
    ];

    public function prompts(): HasMany
    {
        return $this->hasMany(SessionPrompt::class, 'manage_session_id');
    }

    public function activePrompt(): HasOne
    {
        return $this->hasOne(SessionPrompt::class, 'manage_session_id')->where('is_active', true);
    }
}
