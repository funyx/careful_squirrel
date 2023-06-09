<?php

namespace Database\Seeders;

use App\Models\Message;
use App\Models\User;
use Illuminate\Database\Seeder;

class MessageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        User::all()->each(function (User $user) {
            // Create 20 messages for each user
            for ($i = 0; $i < 10; $i++) {
                Message::factory()->state([
                    'sender_id' => $user->id,
                    'recipient_id' => User::inRandomOrder()->first()->id,
                ])->create();
            }
            // Create additional messages without recipient
            for ($i = 0; $i < 10; $i++) {
                Message::factory()->state([
                    'sender_id' => $user->id,
                    'recipient_id' => null,
                ])->create();
            }
        });
    }
}
