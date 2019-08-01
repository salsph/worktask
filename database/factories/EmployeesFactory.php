<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Employee;
use Faker\Generator as Faker;

$factory->define(Employee::class, function (Faker $faker) {
    return [
        'name' => $faker->unique()->name,
        'employee_date' => $faker->date('Y-m-d', 'now'),
        'phone' => $faker->unique()->numerify('+380 (##) ### ## ##'),
        'email' => $faker->unique()->safeEmail,
        'salary' => $faker->randomFloat(3, 0, 500),
        'photo' => '/images/employees/default.jpg',
        'head' => '0',
        'position' => $faker->numberBetween(1, 250),
        'admin_created_id' => '1',
        'admin_updated_id' => '1'
    ];
});
