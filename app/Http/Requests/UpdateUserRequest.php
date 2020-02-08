<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class UpdateUserRequest
 * Обьект запроса обновления данных пользователя
 *
 * @package App\Http\Requests
 */
class UpdateUserRequest extends FormRequest
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
            // если передано в запросе, длиной до 255 символов
            'name' => 'nullable|sometimes|max:255',
            // если передано в запросе, уникальное в таблице users по полю email кроме текущего значения
            'email' => 'nullable|sometimes|email|unique:users,email,' . $this->user,
            // если передано в запросе, размером [8 .. 255] символов
            'password' => 'nullable|sometimes|min:8|max:255',
        ];
    }
}
