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
        factory(App\Models\User::class, 50)->create();
        factory(App\Models\Service::class, 5000)->create();
    }
}
