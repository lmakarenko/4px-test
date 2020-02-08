<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

/**
 * Маршруты системы аутентификации
 */
Auth::routes();

/**
 * Маршруты главной страницы
 */
Route::get('/', 'HomeController@index')->name('index');
Route::get('/home', 'HomeController@index')->name('home');

/**
 * Маршруты для аутентифицированных пользователей
 */
Route::group(['middleware' => 'auth'], function () {
    /**
     * Маршруты для ресурса пользователей
     */
    Route::resource('users', 'UserController');

    /**
     * Маршруты для ресурса отделов
     */
    Route::resource('sections', 'SectionController');

    /**
     * Маршруты для доступа к файлам-логотипам отделов (локальное хранилище)
     */
    Route::get('/public/logo/{filename}', function($filename) {
        // если запраиваемый файл существует в локальном хранилище
        if(Storage::disk('logo')->exists($filename)) {
            // создать ответ с файлом
            return response()->file(storage_path('app/logo') . '/' . $filename);
        } else {
            // выбросить исключение (код 404)
            abort(404);
        }
    });
});
