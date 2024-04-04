<?php

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Container\BindingResolutionException;

class UserRoleSeeder extends Seeder
{
    public function run()
    {
        // Mengubah kolom level menjadi role_id
        User::where('level', 'admin')->update(['role_id' => 1]); // ID role admin
        User::where('level', 'rm')->update(['role_id' => 2]); // ID role user
        User::where('level', 'dokter')->update(['role_id' => 3]);
    }
}
