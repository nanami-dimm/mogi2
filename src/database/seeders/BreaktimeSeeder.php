<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Breaktime;
use App\Models\Attendance;

class BreaktimeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('breaktimes')->insert([
            [
                'breakStart'=>'2025-01-01,12:00:00',
                'breakEnd'=>'2025-01-01,13:00:00',
                'breakDuration'=>60,
               
            ],
            [
                'breakStart'=>'2025-01-01,17:30:00',
                'breakEnd'=>'2025-01-01,18:00:00',
                'breakDuration'=>30,
                
            ],

            
        ]);

        
    }
}
