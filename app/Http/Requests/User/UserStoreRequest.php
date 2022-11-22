<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserStoreRequest extends FormRequest
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
        // Я игрался с этим куском долго и думал куда его запихнуть, здесь его место?
        // Он был в контроллере, но я перенес его сюда и он тут красиво лежит
        $token = Str::random('32');
        $tokens = [
            'tokenСlean' => $token,
            'token' => Hash::make($token)
        ];
        $this->merge($tokens);
        // Конец куска)


        return [
            'name' => ['required', 'string', 'max:155'],
            'email' => ['required', 'email', 'max:255'],
            'country_id' => ['required', 'integer'],
        ];
    }
}
