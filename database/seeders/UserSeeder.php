<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

use App\Models\User;

use Uuid;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // If no entry has been record before
        if (User::count() < 1) {
            $user = new User;
            $user->guid = Uuid::generate()->string;
            $user->name = "test";
            $user->email = "test@test.com";
            $user->password = bcrypt("test");
            $user->save();
        }
    }
}
