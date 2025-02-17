<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AttendanceBreaktimeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('attendance_breaktime')->insert([
            [
                'attendance_id' => '1',
                'breaktime_id' => '1',
            ],
            [
                'attendance_id' => '1',
                'breaktime_id' => '2',
            ],
        ]);
    }
}
