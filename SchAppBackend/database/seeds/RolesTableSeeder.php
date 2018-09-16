<?php

use Illuminate\Database\Seeder;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('roles')->insert([
        	'name' => 'SuperAdmin',
        	'access_level' => 1
        ]);

        DB::table('roles')->insert([
        	'name' => 'Admin',
        	'access_level' => 2
        ]);

        DB::table('roles')->insert([
        	'name' => 'HOD',
        	'access_level' => 3
        ]);

        DB::table('roles')->insert([
        	'name' => 'Lecturer',
        	'access_level' => 4
        ]);

        DB::table('roles')->insert([
        	'name' => 'Junior Lecturer',
        	'access_level' => 5
        ]);

        DB::table('roles')->insert([
        	'name' => 'Secretary',
        	'access_level' => 6
        ]);

        DB::table('roles')->insert([
        	'name' => 'Deen',
        	'access_level' => 2
        ]);
        
        DB::table('roles')->insert([
        	'name' => 'BURSAR',
        	'access_level' => 2
        ]);

        DB::table('roles')->insert([
        	'name' => 'VC',
        	'access_level' => 1
        ]);

        DB::table('roles')->insert([
        	'name' => 'Registrar',
        	'access_level' => 1
        ]);
    }
}
