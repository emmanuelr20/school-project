<?php

use Illuminate\Database\Seeder;

class FacultiesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('faculties')->insert([
        	'name' => 'Physical Science',
        	'dean' => 1
        ]);

        DB::table('faculties')->insert([
        	'name' => 'Engineering',
        	'dean' => 2
        ]);
    }
}
