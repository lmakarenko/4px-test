<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class StoreSectionRequest
 * Обьект запроса сохранения данных раздела
 *
 * @package App\Http\Requests
 */
class StoreSectionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            // обязательное поле не больше 255 символов
            'name' => 'required|max:255',
            // поле может быть null, длиной не больше 65535 байт (тип MySQL TEXT)
            'description' => 'nullable|max:65535',
            // поле может быть null, если передано в запросе, файл успешно загружен, файл с срасширениями png,jpg,jpeg, длиной не больше 100 Кб
            'logo' => 'nullable|sometimes|file|mimes:png,jpg,jpeg|max:100000',
            // поле может быть null, массив
            'users' => 'nullable|sometimes|array',
            // элементы массива users - числа
            'users.*' => 'numeric',
        ];
    }
}
