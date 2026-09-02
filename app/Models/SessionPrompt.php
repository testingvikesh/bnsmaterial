<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SessionPrompt extends Model
{
    use HasFactory;

    protected $fillable = [
        'manage_session_id',
        'title',
        'body',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function session(): BelongsTo
    {
        return $this->belongsTo(ManageSession::class, 'manage_session_id');
    }

    public function makeSoleActive(): void
    {
        static::query()
            ->where('manage_session_id', $this->manage_session_id)
            ->where('id', '!=', $this->id)
            ->update(['is_active' => false]);

        if (! $this->is_active) {
            $this->forceFill(['is_active' => true])->save();
        }
    }

    /**
     * @param  array<string, string>  $snapshot
     */
    public function filled(array $snapshot): string
    {
        $values = [
            'memberName' => (string) ($snapshot['memberName'] ?? ''),
            'businessName' => (string) ($snapshot['businessName'] ?? ''),
            'businessCategory' => (string) ($snapshot['businessCategory'] ?? ''),
            'businessIntroduction' => (string) ($snapshot['businessIntroduction'] ?? ''),
            'mainProduct' => (string) ($snapshot['mainProduct'] ?? ''),
            'businessLocation' => (string) ($snapshot['businessLocation'] ?? ''),
            'businessAddress' => (string) ($snapshot['businessAddress'] ?? ''),
            'whatsapp' => (string) ($snapshot['whatsapp'] ?? ''),
            'sessionName' => (string) ($snapshot['sessionName'] ?? ''),
            'sessionDetails' => (string) ($snapshot['sessionDetails'] ?? ''),
            'city' => (string) ($snapshot['city'] ?? ''),
        ];

        $aliases = [
            'memberName' => ['memberName', 'member_name', 'Member Name', 'member'],
            'businessName' => ['businessName', 'business_name', 'Business Name', 'business'],
            'businessCategory' => ['businessCategory', 'business_category', 'Business Category', 'category'],
            'businessIntroduction' => ['businessIntroduction', 'business_introduction', 'Business Introduction', 'introduction'],
            'mainProduct' => ['mainProduct', 'main_product', 'Main Product', 'products'],
            'businessLocation' => ['businessLocation', 'business_location', 'Business Location', 'location'],
            'businessAddress' => ['businessAddress', 'business_address', 'Business Address', 'address'],
            'whatsapp' => ['whatsapp', 'WhatsApp', 'phone'],
            'sessionName' => ['sessionName', 'session_name', 'Session Name'],
            'sessionDetails' => ['sessionDetails', 'session_details', 'Session Details'],
            'city' => ['city', 'City'],
        ];

        $body = (string) $this->body;
        foreach ($aliases as $key => $names) {
            foreach ($names as $name) {
                $body = str_ireplace('{{'.$name.'}}', $values[$key], $body);
            }
        }

        return $body;
    }
}
