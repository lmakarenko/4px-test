<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class StoreUserRequest
 * Обьект запроса сохранения данных пользователя
 *
 * @package App\Http\Requests
 */
class StoreUserRequest extends FormRequest
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
            // обязательное поле, уникальное в таблице users по полю email
            'email' => 'required|email|unique:users,email',
            // обяхательное поле, размером [8 .. 255] символов
            'password' => 'required|min:8|max:255',
        ];
    }
}
