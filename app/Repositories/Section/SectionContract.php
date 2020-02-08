<?php


namespace App\Repositories\Section;

/**
 * Interface SectionContract
 * Интерфейс репозиториев для работы с данными отделов
 *
 * @package App\Repositories\Section
 */
interface SectionContract
{
    public function getAll();

    public function getAllPaginate($n);

    public function findById($id);

    public function store($data);

    public function updateByid($id, $data);

    public function destroyById($id);
}
