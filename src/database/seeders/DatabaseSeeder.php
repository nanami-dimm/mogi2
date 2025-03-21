<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
       $this->call(AttendancesTableSeeder::class);
       $this->call(AdminSeeder::class);
       $this->call(BreaktimeSeeder::class);
       $this->call(AttendanceBreaktimeSeeder::class);
       $this->call(UsersTableSeeder::class);
    }
}
