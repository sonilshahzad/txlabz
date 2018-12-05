<?php


use Faker\Generator as Faker;


$factory->define(App\News::class, function (Faker $faker) {

    $news = App\News::pluck('id')->toArray();
    
    return [
        'created_at' => now(),
        'updated_at' => now(),
        'title' => str_random(10),
        'image' => str_random(10),
        'description' => str_random(100)
    ];
});