<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            [
                'name'=>'山田太郎',
                'email'=>'yamada123@example.com',
                'password'=>'yamdatarou',
            ],
            [
                'name'=>'西怜奈',
                'email'=>'reina123@example.com',
                'password'=>'nisireina',
            ],
            [
                'name'=>'増田一世',
                'email'=>'ichiyo123@example.com',
                'password'=>'masudaichiyo',
            ],
            [
                'name'=>'山本敬吉',
                'email'=>'keikichi123@example.com',
                'password'=>'yamamotokeikichi',
            ],
            
        ]);
    }
}
