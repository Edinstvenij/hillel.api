<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Auth\TokenRequest;
use App\Models\User;
use http\Client\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Nette\Schema\ValidationException;
use Psy\Exception\ErrorException;

class AuthController
{
    /**
     * @param TokenRequest $request
     * @return mixed
     * @throw ValidationException
     */
    public function createToken(TokenRequest $request)
    {
        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->token, $user->token)) {
            throw new ValidationException('Param is invalid');
        } else {
            return $user->createToken($request->post('device_name'))->plainTextToken;
        }
    }

    /**
     * @param integer $id
     * @throws ErrorException
     *
     */
    public function verify(int $id)
    {
        $user = User::find($id);

        if ($user->verified_at !== null) {
            return throw new ErrorException('user is verify');
        }

        if (request()->has('token') || Hash::check(request()->get('token'), $user->token)) {
            return throw new ErrorException('token is not find');
        }

        $user->verified_at = now();
        $user->save();
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Contracts\Foundation\Application|\Illuminate\Http\Response
     */
    public function logout(Request $request): \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Contracts\Foundation\Application|\Illuminate\Http\Response
    {
        $request->user()->currentAccessToken()->delete();
        return response('Successfully deleted', 200, ['Content-Type' => 'application/json']);
        // Нужно ли везде возвращать ответы как тут? Или то что вернулось в статусе ответа 200 этого достаточно?
    }

}
