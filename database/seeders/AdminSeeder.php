<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        DB::table('users')->insert([
          'username' => 'admin',
          'email' => 'admin@admin.com',
          'password' => Hash::make('123456789'),
          'isAdmin' => true,
          'entryDate' => Carbon::now()
        ]);
    }
}
