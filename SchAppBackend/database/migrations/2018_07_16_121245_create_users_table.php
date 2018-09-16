<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('middle_name')->nullable();
            $table->string('email')->unique()->length(191);
            $table->string('telephone')->nullable();
            $table->string('staff_id')->unique()->length(191);
            $table->integer('department_id');
            $table->boolean('is_active')->default(true);
            $table->boolean('is_academic')->default(true);
            $table->boolean('is_suspended')->default(false);
            $table->string('password');
            $table->string('password_reset')->nullable();
            $table->string('avatar_url')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
