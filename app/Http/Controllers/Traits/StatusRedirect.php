<?php


namespace App\Http\Controllers\Traits;

/**
 * Trait StatusRedirect
 * Функионал для редиректов на индексный маршрут, определенный в свойстве класса контроллера
 *
 * @package App\Http\Controllers\Traits
 */
trait StatusRedirect
{
    /**
     * Маршрут индексного представления
     *
     * @var string
     */
    protected $indexRoute;

    /**
     * Логика формирования имени маршрута для редиректа
     *
     * @param null $route
     * @return string|null
     */
    protected function getRouteName($route = null)
    {
        return ($route ?? $this->indexRoute);
    }

    /**
     * Редирект на указанный маршрут в случае успеха операции
     *
     * @param $msg
     * @param null $route
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function redirectSuccess($msg, $route = null)
    {
        return redirect()->route($this->getRouteName($route))->with('success', $msg);
    }

    protected function redirectFailure($msg, $route = null)
    {
        return redirect()->route($this->getRouteName($route))->with('error', $msg);
    }

    /**
     * Редирект на указанный маршрут в случае ошибки, помещает контейнер ошибок в сессию
     *
     * @param $errors
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function redirectWithErrors($errors, $route = null)
    {
        return redirect()->route($this->getRouteName($route))->withErrors($errors);
    }
}
