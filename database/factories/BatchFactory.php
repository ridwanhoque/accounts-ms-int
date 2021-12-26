<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Batch;
use Faker\Generator as Faker;

$factory->define(App\Batch::class, function (Faker $faker) {
    return [
        'company_id' => 1,
        'created_by' => 1,
        'updated_by' => 1,
        'name' => $faker->name
    ];
});
