<?php

namespace App\Http\Controllers;

use App\Repositories\User\UserContract,
    App\Http\Requests\StoreUserRequest,
    App\Http\Requests\UpdateUserRequest;

/**
 * Class UserController
 * Контроллер для обработки запросов при работе с пользователями
 *
 * @package App\Http\Controllers
 */
class UserController extends Controller
{
    /**
     * Репозиторий пользователей
     *
     * @var UserContract
     */
    protected $repo;

    /**
     * UserController constructor.
     * Внедрение зависимости от репозитория пользователей по интерфейсу
     *
     * @param UserContract $repo
     */
    public function __construct(UserContract $repo)
    {
        $this->repo = $repo;
    }

    /**
     * Показывает страницу листинга пользователей с пагинацией
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $users = $this->repo->getAllPaginate(10);
        return view('users.index', ['users' => $users]);
    }

    /**
     * Показ формы создания пользователя
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        return view('users.create');
    }

    /**
     * Сохранение данных нового пользователя
     *
     * @param StoreUserRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StoreUserRequest $request)
    {
        $validated = $request->validated();
        $this->repo->store($validated);
        return redirect()->route('users.index')->with('success', 'User created!');
    }

    /**
     * Показ формы редактирования пользователя по id
     *
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($id)
    {
        $user = $this->repo->findById($id);
        return view('users.edit', compact('user'));
    }

    /**
     * Обновление данных пользователя по id
     *
     * @param $id
     * @param UpdateUserRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update($id, UpdateUserRequest $request)
    {
        $validated = $request->validated();
        $this->repo->updateById($id, $validated);
        return redirect()->route('users.index')->with('success', 'User updated!');
    }

    /**
     * Удаление пользователя по id
     *
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $this->repo->destroyById($id);
        return redirect()->route('users.index')->with('success', 'User deleted!');
    }
}
