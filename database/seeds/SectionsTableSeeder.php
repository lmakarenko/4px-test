<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

/**
 * Class SectionsTableSeeder
 * Наполнение данными таблицы разделов sections, исопльзуя фабрику разделов
 *
 */
class SectionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // очищаем таблицу отделов
        DB::table('sections')->delete();
        // генерим 15 отделов используя фабрику
        factory(App\Section::class, 15)->create();
    }
}
