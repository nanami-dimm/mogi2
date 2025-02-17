<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Attendance;
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
                
                'punchIn'=>'09:00:00',
                'punchOut'=>'18:00:00',
                'workDuration' => 480,
                'created_at' => '2025-01-01',
                'updated_at' => '2025-01-01',
            ],
        ]);

        

    }
}
