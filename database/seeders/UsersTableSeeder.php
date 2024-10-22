<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::where('email','desmond@optimization.my')->first();
        if(!$user){

            $user = new User();
            $user->name = 'Desmond';
            $user->email = 'desmond@optimization.my';
            $user->password = Hash::make('admin123');
            $user->save();
        }
    }
}
