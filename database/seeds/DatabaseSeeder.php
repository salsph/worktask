<?php


use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        App\Employee::truncate();
        App\Position::truncate();

        $this->call('PositionSeeder');
        $this->call('EmployeesTableSeeder');

        Model::reguard();
    }
}
