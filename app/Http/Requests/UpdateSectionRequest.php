<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class UpdateSectionRequest
 * Обьект запроса обновления данных раздела
 *
 * @package App\Http\Requests
 */
class UpdateSectionRequest extends FormRequest
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
            // если передано, обязательное поле не больше 255 символов
            'name' => 'sometimes|required|max:255',
            // поле может быть null, если передано в запросе, длиной не больше 65535 байт (тип MySQL TEXT)
            'description' => 'nullable|sometimes|max:65535',
            // поле может быть null, если передано в запросе, файл успешно загружен, файл с срасширениями png,jpg,jpeg, длиной не больше 100 Кб
            'logo' => 'nullable|sometimes|file|mimes:png,jpg,jpeg|max:100000',
            // поле может быть null, если передано в запросе, массив
            'users' => 'nullable|sometimes|array',
            // элементы массива users - числа
            'users.*' => 'numeric',
        ];
    }
}
