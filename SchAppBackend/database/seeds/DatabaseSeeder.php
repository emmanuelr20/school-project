<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call('UsersTableSeeder');
        $this->call('PostsTableSeeder');
        $this->call('PollsTableSeeder');
        $this->call('RolesTableSeeder');
        $this->call('DepartmentsTableSeeder');
        $this->call('FacultiesTableSeeder');
        $this->call('RoleUserTableSeeder');
    }
}
