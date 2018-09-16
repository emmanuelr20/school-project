<?php

use Illuminate\Database\Seeder;
use Faker\Generator as Faker;
use Carbon\Carbon;
use App\Models\Option;
use App\Models\Image;

class PollsTableSeeder extends Seeder
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

    	for ($i=0; $i < 100 ; $i++) { 
    		DB::table('polls')->insert([
	        	'title' => $this->faker->sentence,
	        	'body' => $this->faker->paragraph . "\n" . $this->faker->paragraph . "\n" . $this->faker->paragraph, 
	        	'is_closed' => random_int(0, 1),
	        	'closing_date' => date_create('now +' . random_int(1, 24) . 'h' ),
	        	'access_level' => random_int(1, 5),
	        	'user_id' => random_int(1, 10),
	        	'department_id' => random_int(1, 5),
	        	'faculty_id' => random_int(1, 2),
				'image_url' => 'post_139/5b908affe8d0eJERSEY2',
				'role_id' => random_int(1, 5),
				'created_at' => Carbon::createFromTimestampMs("1534934" . random_int(246874, 999999)),
				'updated_at' => Carbon::createFromTimestampMs("1534934" . random_int(246874, 999999))
			]);
			for ($j=0; $j < 4; $j++) { 
				Option::create([
					"body" => $this->faker->sentence,
					"poll_id" => $i
				]);
			}
    	}
    }
}
