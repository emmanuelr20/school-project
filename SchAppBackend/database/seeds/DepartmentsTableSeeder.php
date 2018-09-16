<?php

use Illuminate\Database\Seeder;

class DepartmentsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('departments')->insert([
        	'name' => 'CSC',
        	'faculty_id' => 1
        ]);

        DB::table('departments')->insert([
        	'name' => 'PHY',
        	'faculty_id' => 1
        ]);

        DB::table('departments')->insert([
        	'name' => 'MTH',
        	'faculty_id' => 1
        ]);


        DB::table('departments')->insert([
        	'name' => 'CSE',
        	'faculty_id' => 2
        ]);

        DB::table('departments')->insert([
        	'name' => 'MEE',
        	'faculty_id' => 2
        ]);

		DB::table('departments')->insert([
        	'name' => 'ELE',
        	'faculty_id' => 2
        ]);

    }
}
