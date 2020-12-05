<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $administrator = User::create([
            'name' => 'Administrator',
            'email' => 'administrator@localhost.com',
            'password' => Hash::make('password'),
        ]);

        $administrator->roles()->sync('1');

        $employee = User::create([
            'name' => 'Employee',
            'email' => 'employee@localhost.com',
            'password' => Hash::make('password'),
        ]);

        $employee->roles()->sync('2');

        $client = User::create([
            'name' => 'Client',
            'email' => 'client@localhost.com',
            'password' => Hash::make('password'),
        ]);

        $client->roles()->sync('3');
    }
}
