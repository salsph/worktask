<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmployeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 256);
            $table->date('employee_date');
            $table->string('phone')->unique();
            $table->string('email')->unique();
            $table->double('salary', 6, 3)->unsigned();
            $table->string('photo', 256)->nullable();
            $table->integer('head');
            $table->integer('position')->unsigned()->index()->nullable();
            $table->integer('level')->nullable()->default(null);
            $table->integer('admin_created_id');
            $table->integer('admin_updated_id');
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
        Schema::dropIfExists('employees');
    }
}
