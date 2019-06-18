<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */
use App\Reply;
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

$factory->define(Reply::class, function (Faker $faker) {
    return [
      'thread_id' => function () {
          return factory('App\Thread')->create()->id;
      },
      'user_id' => function () {
          return factory('App\User')->create()->id;
      },
      'body'    => $faker->paragraph
    ];
});
