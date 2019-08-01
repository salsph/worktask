<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Position;
use Faker\Generator as Faker;

$factory->define(Position::class, function (Faker $faker) {
    return [
        'name' => $faker->unique()->jobTitle,
        'admin_created_id' => '1',
        'admin_updated_id' => '1'
    ];
});
