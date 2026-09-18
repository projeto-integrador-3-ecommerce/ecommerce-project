<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\Hash;
// importando a classe
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // criando um usuário seed de teste no banco
        User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => Hash::make('123456'),
        ]);
    }
}
