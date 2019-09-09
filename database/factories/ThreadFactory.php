<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */
use App\Thread;
use Illuminate\Support\Str;
use Faker\Generator as Faker;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(Thread::class, function (Faker $faker) {
    $title = $faker->sentence;

    return [
        'user_id' => function () {
            return factory('App\User')->create()->id;
        },
        'channel_id' => function () {
            return factory('App\Channel')->create()->id;
        },
        'title'   => $title,
        'body'    => $faker->paragraph,
        'visits'  => 0,
        'slug'    => str_slug($title)
    ];
});
