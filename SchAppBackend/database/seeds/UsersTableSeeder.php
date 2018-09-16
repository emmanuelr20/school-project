<?php

use Illuminate\Database\Seeder;
use Faker\Generator as Faker;

class UsersTableSeeder extends Seeder
{
	protected $faker;

	function __construct(Faker $faker)
	{
		$this->faker = $faker;
	}
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i=0; $i < 15; $i++) { 
        	DB::table('users')->insert([
	        	'first_name' => $this->faker->firstName,
	        	'last_name' => $this->faker->lastName,
	        	'middle_name' => $this->faker->firstName,
	        	'email' => $this->faker->email,
	        	'telephone' => $this->faker->phoneNumber,
	        	'staff_id' => ['PSC', 'ENG'][random_int(0, 1)] . random_int(1000000, 9999999),
	        	'department_id' => random_int(1, 6),
	        	'is_active' => true,
	        	'is_academic' => true,
				'is_suspended' => false,
				'avatar_url' => 'post_139/avatar',
	        	'password' => app('hash')->make('qwerty12')
	        ]);
        }
    }
}
