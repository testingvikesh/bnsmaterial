<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::query()->updateOrCreate(
            ['email' => 'admin@material.test'],
            [
                'name' => 'Super Admin',
                'password' => 'password',
                'role' => User::ROLE_ADMIN,
                'phone' => '9999999999',
                'status' => 'active',
                'email_verified_at' => now(),
            ]
        );

        User::query()->updateOrCreate(
            ['email' => 'staff@material.test'],
            [
                'name' => 'Staff User',
                'password' => 'password',
                'role' => User::ROLE_STAFF,
                'phone' => '8888888888',
                'status' => 'active',
                'email_verified_at' => now(),
            ]
        );

        $session = \App\Models\ManageSession::query()->firstOrCreate(
            ['name' => 'Orientation Session'],
            ['details' => 'Welcome session covering course overview, timings and materials.']
        );

        \App\Models\SessionPrompt::query()->firstOrCreate(
            [
                'manage_session_id' => $session->id,
                'title' => 'Orientation outline prompt',
            ],
            [
                'body' => "You are a training facilitator.\n\nCreate a clear orientation outline for this session.\nInclude welcome, agenda, materials needed, and 5 discussion questions.",
                'is_active' => true,
            ]
        );
    }
}
