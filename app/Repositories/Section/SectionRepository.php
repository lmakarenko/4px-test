<?php


namespace App\Repositories\Section;


use App\Section;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

/**
 * Class SectionRepository
 * Реализация репозитория для работы с данными отделов
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
     * SectionRepository constructor.
     * Внедрение зависимости от модели отделов
     *
     * @param Section $section
     */
    function __construct(Section $section) {
        $this->section = $section;
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
     * @return mixed
     */
    public function store($data)
    {
        // Сохранение файла логотипа
        if(isset($data['logo'])) {
            $this->saveLogo($data['logo']);
        }
        // Наполнение модели отделов данными
        $this->section->fill([
            'name' => $data['name'],
            'description' => $data['description'],
            'logo' => $logoImageHashName ?? '',
        ]);
        // Сохранение модели в БД
        if($this->section->save() && isset($data['users'])) {
            // Добавление связей отдела с пользователями в pivot-таблицу
            $this->section->users()->attach($data['users']);
        }
        return $this->section->id;
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
        // Сохранение файла логотипа
        if(isset($data['logo'])) {
            $newData['logo'] = $this->saveLogo($data['logo'], true);
        }
        // Наполнение модели отделов данными
        $this->section->fill($newData);
        // Сохранение модели в БД
        if($this->section->save() && isset($data['users'])) {
            // Синхронизация связей отдела с пользователями в pivot-таблице, удаление старых связей для данного отдела
            $this->section->users()->sync($data['users']);
        }
        return $this->section->id;
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
     * @return int
     */
    public function destroyById($id)
    {
        $this->section = $this->findById($id);
        Storage::disk('logo')->delete($this->section->logo);
        return $this->section->destroy($id);
    }
}
