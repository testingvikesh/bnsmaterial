<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    public static function nextMemberId(): string
    {
        $last = static::query()->orderByDesc('id')->value('member_id');
        $num = 1;

        if (is_string($last) && preg_match('/(\d+)$/', $last, $matches)) {
            $num = (int) $matches[1] + 1;
        }

        return 'MEM-'.str_pad((string) $num, 4, '0', STR_PAD_LEFT);
    }

    /**
     * All member profile fields grouped for list / form / view.
     *
     * @return array<string, array<string, array{label: string, type?: string}>>
     */
    public static function profileGroups(): array
    {
        return [
            'Personal' => [
                'member_id' => ['label' => 'Member Id'],
                'name' => ['label' => 'Name'],
                'email' => ['label' => 'Email', 'type' => 'email'],
                'phone' => ['label' => 'Mobile'],
                'whatsapp' => ['label' => 'WhatsApp'],
                'age' => ['label' => 'Age', 'type' => 'number'],
                'gender' => ['label' => 'Gender', 'type' => 'select', 'options' => ['male' => 'Male', 'female' => 'Female', 'other' => 'Other']],
                'education_qualification' => ['label' => 'Education'],
                'city' => ['label' => 'City'],
                'state' => ['label' => 'State'],
                'batch_name' => ['label' => 'Batch'],
                'current_status' => ['label' => 'Current Status', 'type' => 'select', 'options' => [
                    'running' => 'Running business',
                    'startup' => 'Startup',
                    'family' => 'Family business',
                    'planning' => 'Planning to start',
                    'job' => 'Job',
                    'other' => 'Other',
                ]],
                'current_status_other' => ['label' => 'Current Status (other)'],
                'status' => ['label' => 'Account Status', 'type' => 'select', 'options' => ['active' => 'Active', 'inactive' => 'Inactive']],
            ],
            'Business' => [
                'business_name' => ['label' => 'Business Name'],
                'business_category' => ['label' => 'Business Category'],
                'business_type' => ['label' => 'Business Type', 'type' => 'select', 'options' => [
                    'proprietorship' => 'Proprietorship',
                    'partnership' => 'Partnership',
                    'pvt_ltd' => 'Private Limited',
                    'llp' => 'LLP',
                    'other' => 'Other',
                ]],
                'business_type_other' => ['label' => 'Business Type (other)'],
                'business_location' => ['label' => 'Business Location'],
                'business_address' => ['label' => 'Business Address', 'type' => 'textarea'],
                'business_description' => ['label' => 'Business Introduction', 'type' => 'textarea'],
                'main_products_services' => ['label' => 'Business Main Product', 'type' => 'textarea'],
                'main_services' => ['label' => 'Additional Services', 'type' => 'textarea'],
                'business_stage' => ['label' => 'Business Stage', 'type' => 'select', 'options' => [
                    'idea' => 'Idea',
                    'startup' => 'Startup',
                    'growing' => 'Growing',
                    'established' => 'Established',
                ]],
                'operating_area' => ['label' => 'Operating Area', 'type' => 'select', 'options' => [
                    'local' => 'Local',
                    'city' => 'City',
                    'state' => 'State',
                    'national' => 'National',
                    'international' => 'International',
                ]],
                'gstin' => ['label' => 'GSTIN'],
                'pan_number' => ['label' => 'PAN'],
                'gst_legal_name' => ['label' => 'GST Legal Name'],
            ],
            'Planning' => [
                'planning_business_name' => ['label' => 'Planning Business Name'],
                'planning_category' => ['label' => 'Planning Category'],
                'planning_location' => ['label' => 'Planning Location'],
                'planning_idea' => ['label' => 'What is this idea?', 'type' => 'textarea'],
                'planning_timeline' => ['label' => 'Planning Timeline'],
                'planning_notes' => ['label' => 'Planning Notes', 'type' => 'textarea'],
                'idea_why' => ['label' => 'Why this idea?', 'type' => 'textarea'],
                'idea_customer' => ['label' => 'Who is it for?'],
                'idea_opportunity' => ['label' => 'Opportunity', 'type' => 'textarea'],
                'idea_existing_skill' => ['label' => 'Existing Skill', 'type' => 'textarea'],
                'idea_want_to_learn' => ['label' => 'Want to Learn', 'type' => 'textarea'],
            ],
            'Digital' => [
                'website_url' => ['label' => 'Website'],
                'instagram' => ['label' => 'Instagram'],
                'facebook' => ['label' => 'Facebook'],
                'linkedin' => ['label' => 'LinkedIn'],
                'youtube' => ['label' => 'YouTube'],
                'google_business' => ['label' => 'Google Business'],
            ],
        ];
    }

    /**
     * @return array<string, array{label: string, type?: string, options?: array<string, string>}>
     */
    public static function profileFields(): array
    {
        $fields = [];
        foreach (static::profileGroups() as $group) {
            $fields = array_merge($fields, $group);
        }

        return $fields;
    }

    public function displayValue(string $field): string
    {
        $meta = static::profileFields()[$field] ?? null;
        $value = $this->{$field};

        if ($value === null || $value === '') {
            return '—';
        }

        if (($meta['type'] ?? '') === 'select' && isset($meta['options'][$value])) {
            return $meta['options'][$value];
        }

        return (string) $value;
    }
}
