<?php
namespace App\Http\Requests;

class PasswordCreateRequest extends Request
{
    /**
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * @return array
     */
    public function rules()
    {
        return [
            'password' => 'required|min:6',
            'email_address' => 'required|email|max:255|unique:user',
        ];
    }
}
