<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use DB;

class AdminsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('admins')->insert(array(
        	array(
                'name' => "Asif Ahmed",
                'email' => 'asifahmed.mist@gmail.com',
                'password' => bcrypt('test1234'),

                'name' => "Meherab Riyan Chowdhury",
                'email' => 'rayanchowdhury07@gmail.com',
                'password' => bcrypt('Riyan1234'),
		    )
		));
    }
}
