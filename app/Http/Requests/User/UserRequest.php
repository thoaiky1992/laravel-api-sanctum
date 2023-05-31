<?php

namespace App\Http\Requests\User;

use App\Http\Requests\CustomRequest;

class UserRequest extends CustomRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'email'         => ['required', 'email', 'string'],
            'name'          => ['required', 'string'],
            'password'      => ['required', 'string'],
            'id'            => ['nullable', 'uuid']
        ];
    }
}
