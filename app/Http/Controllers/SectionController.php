<?php

namespace App\Http\Controllers;

use App\Repositories\User\UserContract;
use App\Repositories\Section\SectionContract;
use App\Http\Requests\StoreSectionRequest;
use App\Http\Requests\UpdateSectionRequest;

/**
 * Class SectionController
 * Контроллер для обработки запросов при работе с разделами
 *
 * @package App\Http\Controllers
 */
class SectionController extends Controller
{
    /**
     * Репозиторий разделов
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
     * Внедрение зависимостей от репозиториев пользователей и разделов по их интерфейсам
     *
     * @param SectionContract $sectionRepo
     * @param UserContract $userRepo
     */
    public function __construct(SectionContract $sectionRepo, UserContract $userRepo)
    {
        $this->sectionRepo = $sectionRepo;
        $this->userRepo = $userRepo;
    }

    /**
     * Показывает страницу листинга разделов с пагинацией
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $sections = $this->sectionRepo->getAllPaginate(4);
        return view('sections.index', ['sections' => $sections]);
    }

    /**
     * Показ формы создания раздела
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        $users = $this->userRepo->getAll();
        return view('sections.create', compact('users'));
    }

    /**
     * Сохранение данных нового раздела
     *
     * @param StoreSectionRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StoreSectionRequest $request)
    {
        $validated = $request->validated();
        $this->sectionRepo->store($validated);
        return redirect()->route('sections.index')->with('success', 'Section created!');
    }

    /**
     * Показ формы редактирования раздела по id
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
     * Обновление данных раздела по id
     *
     * @param $id
     * @param UpdateSectionRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update($id, UpdateSectionRequest $request)
    {
        $validated = $request->validated();
        $this->sectionRepo->updateById($id, $validated);
        return redirect()->route('sections.index')->with('success', 'Section updated!');
    }

    /**
     * Удаление раздела по id
     *
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $this->sectionRepo->destroyById($id);
        return redirect()->route('sections.index')->with('success', 'Section deleted!');
    }
}
