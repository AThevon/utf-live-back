<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AdminUserProdSeeder extends Seeder
{
    public function run(): void
    {
        $admins = [
            ['name' => 'Adrien', 'email' => 'adrienthevon@gmail.com', 'password' => 'Wuosian4utf@'],
            ['name' => 'Victor', 'email' => 'denayvic.contact@gmail.com', 'password' => 'victordenaynetflix'],
            ['name' => 'Oniji', 'email' => 'lpuyraud@gmail.com', 'password' => 'victordenaynetflix'],
        ];

        foreach ($admins as $admin) {
            User::updateOrCreate(
                ['email' => $admin['email']],
                [
                    'name' => $admin['name'],
                    'password' => Hash::make($admin['password']),
                ]
            );
        }
    }
}
