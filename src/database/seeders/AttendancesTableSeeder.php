<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AttendancesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('attendances')->insert([
            [   
                'user_id'=>1,
                'date_column'=>'2024-12-01',
                'punchIn'=>'09:00:00',
                'punchOut'=>'18:00:00',
                'breakStart'=>'12:00:00',
                'breakEnd'=>'13:00:00',
                'breakDuration' => 60,
                'workDuration' => 480,
                
            ],
        ]);
    }
}
