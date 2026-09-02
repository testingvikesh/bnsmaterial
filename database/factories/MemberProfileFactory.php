<?php

namespace Database\Factories;

use App\Models\MemberProfile;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\MemberProfile>
 */
class MemberProfileFactory extends Factory
{
    protected $model = MemberProfile::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'whatsapp' => fake()->numerify('98########'),
            'member_id' => 'MEM-'.str_pad((string) fake()->unique()->numberBetween(1, 9999), 4, '0', STR_PAD_LEFT),
            'business_name' => fake()->company(),
            'business_category' => fake()->randomElement(['Retail', 'Manufacturing', 'Services', 'Trading']),
            'business_location' => fake()->city(),
            'business_address' => fake()->address(),
            'business_description' => fake()->sentence(12),
            'main_products_services' => fake()->words(3, true),
        ];
    }
}
