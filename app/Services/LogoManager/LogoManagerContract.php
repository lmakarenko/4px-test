<?php

namespace App\Services\LogoManager;

/**
 * Interface LogoManagerContract
 * Интерфейс для реализаций менеджера логотипов (изображений)
 *
 * @package App\Services\LogoManager
 */
interface LogoManagerContract
{
     public function store($file);

     public function delete($file);

     public function resize($file);
}
