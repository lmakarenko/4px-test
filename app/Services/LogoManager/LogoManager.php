<?php

namespace App\Services\LogoManager;

use App\Services\Loggers\ErrorLogger\ErrorLoggerContract;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Http\File;
use Illuminate\Http\UploadedFile;
use Intervention\Image\Facades\Image;
use Exception;
use InvalidArgumentException;

/**
 * Class LogoManager
 * Менеджер файлов-логотипов (изображений)
 * Реализует сохранение, удаление и пропорциональное изменение размеров логотипа
 *
 * @package App\Services\LogoManager
 */
class LogoManager implements LogoManagerContract
{
    /**
     * Локальное файловое хранилище
     *
     * @var Filesystem
     */
    protected $fileStorage;

    /**
     * Логгер ошибок
     *
     * @var ErrorLoggerContract
     */
    protected $errorLogger;

    /**
     * LogoManager constructor.
     *
     *
     * @param Filesystem $fileStorage
     * @param ErrorLoggerContract $errorLogger
     */
    public function __construct(Filesystem $fileStorage, ErrorLoggerContract $errorLogger)
    {
        $this->fileStorage = $fileStorage;
        $this->errorLogger = $errorLogger;
    }

    /**
     * Сохраняет логотип в локальное файловое хранилище
     *
     * @param $file
     * @return bool|string
     */
    public function store($file)
    {
        try {
            if(is_string($file)) {
                $fileObj = new File($file);
                $this->fileStorage->put('/logo', $fileObj);
                $logoImageHashName = $fileObj->hashName();
            } else if($file instanceof UploadedFile) {
                $file->store('/logo');
                $logoImageHashName = $file->hashName();
            } else {
                throw new InvalidArgumentException('file argument has invalid type');
            }
            if('' == $logoImageHashName) {
                throw new Exception('hash file is empty');
            }
            $logoFullPath = $this->fileStorage->path($logoImageHashName);
            $this->resize($logoFullPath);
            return $logoImageHashName;
        } catch (Exception $ex) {
            $this->errorLogger->errorByException('Logo store error!', $ex);
        }
        return false;
    }

    /**
     * Удаляет логотип из файлового хранилища
     *
     * @param $file
     */
    public function delete($file)
    {
        // Удаление старого изображения из локального хранилища
        try {
            if(!$this->fileStorage->delete($file)) {
                throw new Exception('delete file error');
            }
        } catch(Exception $ex) {
            $this->errorLogger->errorByException('Logo delete error!', $ex);
        }
    }

    /**
     * Изменяет размер файла пропорционально ширине
     *
     * @param $file
     */
    public function resize($file)
    {
        // Изменение размеров изображения
        $logoImage = Image::make($file)->fit(100);
        $logoImage->save($file);
    }
}
