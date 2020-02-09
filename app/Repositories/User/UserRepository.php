<?php

namespace App\Repositories\User;

use App\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * Class UserRepository
 * Реализация репозитория для работы с данными пользователей
 *
 * @package App\Repositories\User
 */
class UserRepository implements UserContract
{
    /**
     * Модель пользователей
     *
     * @var User
     */
    protected $user;

    /**
     * UserRepository constructor.
     * Внедрение зависимости от модели пользователей
     *
     * @param User $user
     */
    function __construct(User $user) {
        $this->user = $user;
    }

    /**
     * Возвращает данные всех пользователей
     *
     * @return mixed
     */
    public function getAll()
    {
        return $this->user->get();
    }

    /**
     * Возвращает данные пользователей с учетом пагинации (кол-во записей на странице - n)
     *
     * @param $n
     * @return mixed
     */
    public function getAllPaginate($n)
    {
        return $this->user->orderBy('id', 'desc')->paginate($n);
    }

    /**
     * Возвращает данные пользователя по id
     *
     * @param $id
     * @return mixed
     */
    public function findById($id)
    {
        return $this->user->findOrFail($id);
    }

    /**
     * Сохраняет данные нового пользователя в БД
     *
     * @param $data
     * @return bool
     */
    public function store($data)
    {
        $this->user->fill([
            'name' => $data['name'],
            'email' => $data['email'],
            'email_verified_at' => now(),
            'password' => Hash::make($data['password']),
            'remember_token' => Str::random(10),
        ]);
        return $this->user->save();
    }

    /**
     * Изменяет данные существующего пользователя в БД по id
     *
     * @param $id
     * @param $data
     * @return bool
     */
    public function updateById($id, $data)
    {
        $this->user = $this->findById($id);
        $newData = [];
        if(isset($data['name'])) {
            $newData['name'] = $data['name'];
        }
        if(isset($data['email'])) {
            $newData['email'] = $data['email'];
            $newData['email_verified_at'] = now();
        }
        if(isset($data['password'])) {
            $newData['password'] = Hash::make($data['password']);
        }
        if(count($newData) > 0) {
            $this->user->fill($newData);
            return $this->user->save();
        }
         return false;
    }

    /**
     * Удаляет пользователя из БД по id
     *
     * @param $id
     * @return int
     */
    public function destroyById($id)
    {
        $this->user = $this->findById($id);
        return $this->user->destroy($id);
    }
}
