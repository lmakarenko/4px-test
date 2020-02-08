<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

/**
 * Class UsersTableSeeder
 * Наполнение данными таблицы пользователей users, используя фабрику пользователей и модель пользователей
 *
 */
class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // очищаем таблицу пользователей
        DB::table('users')->delete();
        // генерим 1 пользователя используя создание через модель
        App\User::create([
            'name' => 'admin',
            'email' => 'admin@test.loc',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'remember_token' => Str::random(10),
        ])
            // добавляем для текущего id пользователя случайное кол-во [1 .. 15] id разделов в pivot-таблицу section_user
            ->sections()->sync($this->getRandomSections(1, 15));
        // генерим 15 пользователей используя фабрику
        factory(App\User::class, 15)->create()->each(function ($user) {
           // добавляем для текущего id пользователя случайное кол-во [1 .. 15] id разделов в pivot-таблицу section_user
            $user->sections()->sync($this->getRandomSections(1, 15));
        });
    }

    /**
     * Выборка данных в виде коллекции из случайного кол-ва разделов,
     * переформатирвоание данных в нужный формат (массив) для синхронизации с pivot- таблицей (метод sync()):
     * [id => [ param1, .. ], .. ]
     *
     * @param int $min
     * @param int $max
     * @return mixed
     */
    protected function getRandomSections($min = 1, $max = 15)
    {
        return App\Section::all('id')->random(mt_rand($min, $max))->reduce(function ($carry, $section) {
            $carry[$section->id] = ['created_at' => now(), 'updated_at' => now()];
            return $carry;
        }, []);
    }
}
