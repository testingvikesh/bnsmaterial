<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MaterialEvent extends Model
{
    public const VIEW = 'view';

    public const READ = 'read';

    protected $fillable = [
        'material_file_id',
        'user_id',
        'manage_session_id',
        'viewer_id',
        'type',
        'ip',
    ];

    public function file(): BelongsTo
    {
        return $this->belongsTo(MaterialFile::class, 'material_file_id');
    }

    public function member(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function session(): BelongsTo
    {
        return $this->belongsTo(ManageSession::class, 'manage_session_id');
    }

    public function viewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'viewer_id');
    }
}
