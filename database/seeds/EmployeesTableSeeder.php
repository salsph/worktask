<?php

use Illuminate\Database\Seeder;

class EmployeesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $workersCount = 50000;
        //$workersCount = 1000;

        factory(App\Employee::class, $workersCount)->create();
        App\Http\Controllers\EmployeeController::setBosses();
    }
}
