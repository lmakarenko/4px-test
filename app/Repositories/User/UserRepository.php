<?php

namespace App\Repositories\User;

use App\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Services\Loggers\ErrorLoggerContract;

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
     * Логгер ошибок
     *
     * @var ErrorLoggerContract
     */
    protected $errorLogger;

    /**
     * UserRepository constructor.
     * Внедрение зависимости от модели пользователей
     *
     * @param User $user
     */
    public function __construct(User $user, ErrorLoggerContract $errorLogger) {
        $this->user = $user;
        $this->errorLogger = $errorLogger;
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
        try {
            $this->user->fill([
                'name' => $data['name'],
                'email' => $data['email'],
                'email_verified_at' => now(),
                'password' => Hash::make($data['password']),
                'remember_token' => Str::random(10),
            ]);
            if(!$this->user->save()) {
                throw new \Exception('model save failed');
            }
            return true;
        } catch(\Exception $ex) {
            $this->errorLogger->errorByException('User create error!', $ex);
        }
        return false;
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
        try {
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
            // Нечего обновлять
            if(count($newData) == 0) {
                return true;
            }
            $this->user->fill($newData);
            if(!$this->user->save()) {
                throw new \Exception('model save failed');
            }
            return true;
        } catch(\Exception $ex) {
            $this->errorLogger->errorByException('User update error!', $ex);
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
        try {
            $this->user = $this->findById($id);
            if(0 == $this->user->destroy($id)) {
                throw new \Exception('0 rows deleted');
            }
            return true;
        } catch(\Exception $ex) {
            $this->errorLogger->errorByException('User delete error!', $ex);
        }
        return false;
    }
}
