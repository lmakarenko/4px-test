<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Section;
use Faker\Generator as Faker;
use Illuminate\Support\Facades\Storage;

$autoIncrement = autoIncrement();

$factory->define(Section::class, function (Faker $faker) use ($autoIncrement) {
    $autoIncrement->next();
    return [
        'name' => $faker->company,
        'description' => $faker->text,
        'logo' => 'logo' . $autoIncrement->current() . '.png',
    ];
});

/**
 * Генератор для счетчика (автоинкремента) созданных фабрикой обьектов
 *
 * @return Generator
 */
function autoIncrement()
{
    for ($i = 0; $i < 1000; $i++) {
        yield $i;
    }
}
