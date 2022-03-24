<?php

use App\Models\User;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    public function run()
    {
        $users = [
            [
                'id'             => 1,
                'name'           => 'Admin',
                'email'          => 'admin@admin.com',
                'password'       => bcrypt('password'),
                'remember_token' => null,
                'id_simpeg'      => '',
                'nip'            => '',
                'no_identitas'   => '',
                'nama'           => '',
                'username'       => '',
                'alamat'         => '',
                'no_hp'          => '',
                'foto_url'       => '',
                'jwt_token'      => '',
            ],
        ];

        User::insert($users);
    }
}
