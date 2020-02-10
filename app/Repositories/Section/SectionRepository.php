<?php


namespace App\Repositories\Section;


use App\Section;
use Illuminate\Support\Facades\DB;
use App\Services\Loggers\ErrorLogger\ErrorLoggerContract;
use App\Services\LogoManager\LogoManagerContract;

/**
 * Class SectionRepository
 * Реализация репозитория для работы с данными отделов (Eloquent)
 *
 * @package App\Repositories\Section
 */
class SectionRepository implements SectionContract
{
    /**
     * Модель отделов
     *
     * @var Section
     */
    protected $section;

    /**
     * Логгер ошибок
     *
     * @var ErrorLoggerContract
     */
    protected $errorLogger;

    /**
     * Менеджер логотипов
     *
     * @var LogoManagerContract
     */
    protected $logoManager;

    /**
     * SectionRepository constructor.
     * Внедрение зависимостей от: модель отделов, логгер ошибок, менеджер логотипов
     *
     * @param Section $section
     * @param ErrorLoggerContract $errorLogger
     * @param LogoManagerContract $logoManager
     */
    public function __construct(Section $section, ErrorLoggerContract $errorLogger, LogoManagerContract $logoManager) {
        $this->section = $section;
        $this->errorLogger = $errorLogger;
        $this->logoManager = $logoManager;
    }

    /**
     * Возвращает данные всех отделов
     *
     * @return mixed
     */
    public function getAll()
    {
        return $this->section->get();
    }

    /**
     * Возвращает данные отделов с учетом пагинации (кол-во записей на странице - n)
     *
     * @param $n
     * @return mixed
     */
    public function getAllPaginate($n)
    {
        return $this->section->orderBy('id', 'desc')->paginate($n);
    }

    /**
     * Возвращает данные отдела по id
     *
     * @param $id
     * @return mixed
     */
    public function findById($id)
    {
        return $this->section->findOrFail($id);
    }

    /**
     * Сохраняет данные нового отдела в БД,
     * если загружен файл логотипа отдела - сохраняет его в локальное хранилище
     *
     * @param $data
     * @return bool|mixed
     */
    public function store($data)
    {
        $newData = [
            'name' => $data['name'],
            'description' => $data['description']
        ];
        try {
            // Сохранение файла логотипа
            if(isset($data['logo'])) {
                $newData['logo'] = $this->logoManager->store($data['logo']);
            }
            // Ручное управление транзакцией
            DB::beginTransaction();
            // Наполнение модели отделов данными
            $this->section->fill($newData);
            // Сохранение модели в БД
            if(!$this->section->save()) {
                throw new \Exception('model save failed');
            }
            if(isset($data['users'])) {
                // Добавление связей отдела с пользователями в pivot-таблицу
                $this->section->users()->attach($data['users']);
            }
            DB::commit();
            return $this->section->id;
        } catch(\Exception $ex) {
            DB::rollback();
            $this->errorLogger->errorByException('Section create error!', $ex);
        }
        return false;
    }

    /**
     * Изменяет данные существующего отдела в БД по id,
     * если загружен новый файл логотипа отдела - сохраняет его в локальное хранилище и удаляет старый
     *
     * @param $id
     * @param $data
     * @return mixed
     */
    public function updateById($id, $data)
    {
        try {
            $this->section = $this->findById($id);
            $newData = [
                'name' => $data['name'],
                'description' => $data['description'],
            ];
            // Сохранение файла логотипа
            if(isset($data['logo'])) {
                // Сохранение нового логотипа в файловое хранилище
                $newData['logo'] = $this->logoManager->store($data['logo']);
                // Удаление предыдущего логотипа
                if('' !== $this->section->logo) {
                    $this->logoManager->delete($this->section->logo);
                }
            }
            // Ручное управление транзакцией
            DB::beginTransaction();
            // Наполнение модели отделов данными
            $this->section->fill($newData);
            // Сохранение модели в БД
            if(!$this->section->save()) {
                throw new \Exception('model save failed');
            }
            if(isset($data['users'])) {
                // Синхронизация связей отдела с пользователями в pivot-таблице, удаление старых связей для данного отдела
                $this->section->users()->sync($data['users']);
            }
            DB::commit();
            return true;
        } catch(\Exception $ex) {
            DB::rollback();
            $this->errorLogger->errorByException('Section update error!', $ex);
        }
        return false;
    }

    /**
     * Удаляет раздел из БД по id
     *
     * @param $id
     * @return bool
     */
    public function destroyById($id)
    {
        try {
            $this->section = $this->findById($id);
            if('' !== $this->section->logo) {
                $this->logoManager->delete($this->section->logo);
            }
            if(0 == $this->section->destroy($id)) {
                throw new \Exception('0 rows deleted');
            }
            return true;
        } catch(\Exception $ex) {
            $this->errorLogger->errorByException('Section delete error!', $ex);
        }
        return false;
    }
}
