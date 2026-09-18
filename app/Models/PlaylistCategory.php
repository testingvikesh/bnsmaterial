<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PlaylistCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'details',
        'sort_order',
    ];

    protected $casts = [
        'sort_order' => 'integer',
    ];

    public function videos(): HasMany
    {
        return $this->hasMany(PlaylistVideo::class);
    }
}
