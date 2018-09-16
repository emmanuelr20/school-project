<?php

use Illuminate\Database\Seeder;

class RoleUserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i=0; $i < 20 ; $i++) { 
    		DB::table('role_user')->insert([
	        	"user_id" => random_int(1, 15),
	        	"role_id" => random_int(1, 6)
	        ]);
    	}
    }
}
