<?php

use Illuminate\Database\Seeder;

class DummieDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Create services
        factory(App\Models\Service::class, 500)->create();

        // Front user
        $data = [
            'name' => 'Tester user',
            'email' => 'tester@lrc.example.com',
            'email_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'remember_token' => Str::random(10),
        ];

        App\Models\User::create($data);

        // Let's create 5 more users
        factory(App\Models\User::class, 5)->create();
    }
}
