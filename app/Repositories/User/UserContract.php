<?php

namespace App\Repositories\User;

/**
 * Interface UserContract
 * Интерфейс репозиториев для работы с данными пользователей
 *
 * @package App\Repositories\User
 */
interface UserContract
{
    public function getAll();

    public function getAllPaginate($n);

    public function findById($id);

    public function store($data);

    public function updateById($id, $data);

    public function destroyById($id);
}
