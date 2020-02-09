<?php


namespace App\Repositories\Section;


use App\Section;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\DB;
use App\Services\Loggers\ErrorLoggerContract;

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
     * SectionRepository constructor.
     * Внедрение зависимости от модели отделов
     *
     * @param Section $section
     */
    public function __construct(Section $section, ErrorLoggerContract $errorLogger) {
        $this->section = $section;
        $this->errorLogger = $errorLogger;
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
                $newData['logo'] = $this->saveLogo($data['logo']);
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
        $this->section = $this->findById($id);
        $newData = [
            'name' => $data['name'],
            'description' => $data['description'],
        ];
        try {
            // Сохранение файла логотипа
            if(isset($data['logo'])) {
                $newData['logo'] = $this->saveLogo($data['logo'], true);
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
     * Сохранение загруженного логотипа в локальное хранилище, возвращает имя сохраненного файла
     *
     * @param UploadedFile $uploadedFile
     * @param bool $deleteOld
     * @return string
     */
    protected function saveLogo(UploadedFile $uploadedFile, $deleteOld = false)
    {
        // TODO: вынести логику в отдельный компонент (напр. менеджер логотипов) и связать через интерфейс с репозиторием
        // Перемещение загруженного файла в локальное хранилище
        $uploadedFile->store('/', 'logo');
        $logoImageHashName = $uploadedFile->hashName();
        $logoFullPath = Storage::disk('logo')->path($logoImageHashName);
        // Изменение размеров изображения
        $logoImage = Image::make($logoFullPath)->fit(100);
        $logoImage->save($logoFullPath);
        // Удаление старого изображения из локального хранилища
        if($deleteOld) {
            Storage::disk('logo')->delete($this->section->logo);
        }
        return $logoImageHashName;
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
            Storage::disk('logo')->delete($this->section->logo);
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
