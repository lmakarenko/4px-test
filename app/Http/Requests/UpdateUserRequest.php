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
            // если передано в запросе, обязательное поле, длиной до 255 символов
            'name' => 'sometimes|required|max:255',
            // если передано в запросе, обязательное поле, уникальное в таблице users по плю email кроме текущего значения
            'email' => 'sometimes|required|email|unique:users,email,' . $this->user,
            // если передано в запросе, обязательное поле, размером [8 .. 255] символов
            'password' => 'sometimes|required|min:8|max:255',
        ];
    }
}
