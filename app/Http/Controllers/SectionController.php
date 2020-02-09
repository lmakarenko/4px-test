<?php

namespace App\Http\Controllers;

use App\Repositories\User\UserContract;
use App\Repositories\Section\SectionContract;
use App\Http\Requests\StoreSectionRequest;
use App\Http\Requests\UpdateSectionRequest;
use App\Http\Controllers\Traits\StatusRedirect;

/**
 * Class SectionController
 * Контроллер для обработки запросов при работе с отделами
 *
 * @package App\Http\Controllers
 */
class SectionController extends Controller
{
    use StatusRedirect;

    /**
     * Репозиторий отделов
     *
     * @var SectionContract
     */
    protected $sectionRepo;

    /**
     * Репозиторий пользователей
     *
     * @var UserContract
     */
    protected $userRepo;

    /**
     * SectionController constructor.
     * Внедрение зависимостей от репозиториев пользователей и отделов по их интерфейсам
     *
     * @param SectionContract $sectionRepo
     * @param UserContract $userRepo
     */
    public function __construct(SectionContract $sectionRepo, UserContract $userRepo)
    {
        $this->sectionRepo = $sectionRepo;
        $this->userRepo = $userRepo;
        $this->indexRoute = 'sections.index';
    }

    /**
     * Показывает страницу листинга отделов с пагинацией
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $sections = $this->sectionRepo->getAllPaginate(4);
        return view('sections.index', ['sections' => $sections]);
    }

    /**
     * Показ формы создания отдела
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        $users = $this->userRepo->getAll();
        return view('sections.create', compact('users'));
    }

    /**
     * Сохранение данных нового отдела
     *
     * @param StoreSectionRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StoreSectionRequest $request)
    {
        $validated = $request->validated();
        if($this->sectionRepo->store($validated)) {
            return $this->redirectSuccess('Section created!');
        } else {
            return $this->redirectFailure('Section create error!');
        }
    }

    /**
     * Показ формы редактирования отдела по id
     *
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($id)
    {
        $users = $this->userRepo->getAll();
        $section = $this->sectionRepo->findById($id);
        return view('sections.edit', compact('section', 'users'));
    }

    /**
     * Обновление данных отдела по id
     *
     * @param $id
     * @param UpdateSectionRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update($id, UpdateSectionRequest $request)
    {
        $validated = $request->validated();
        if($this->sectionRepo->updateById($id, $validated)) {
            return $this->redirectSuccess('Section updated!');
        } else {
            return $this->redirectFailure('Section update error!');
        }
    }

    /**
     * Удаление отдела по id
     *
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        if($this->sectionRepo->destroyById($id)) {
            return $this->redirectSuccess('Section deleted!');
        } else {
            return $this->redirectFailure('Section delete error!');
        }
    }

}
