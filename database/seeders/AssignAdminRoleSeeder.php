<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class AssignAdminRoleSeeder extends Seeder
{
    public function run()
    {
        // ubah email ini sesuai user admin yang sudah ada
        $adminEmail = 'admin@example.com';

        $user = User::where('email', $adminEmail)->first();

        if ($user) {
            $user->assignRole('super-admin');
            $this->command->info("Assigned role 'super-admin' to {$adminEmail}");
        } else {
            $this->command->warn("User with email {$adminEmail} not found. Create the user or change the email in seeder.");
        }
    }
}
