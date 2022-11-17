<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Filters\UsersFilter;
use App\Mail\VerifiedMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Psy\Exception\ErrorException;

class UserController extends Controller
{
    /**
     * @throws ErrorException
     */
    public function verify(int $id)
    {
        $user = User::find($id);

        if ($user->verified_at !== null) {
            return throw new ErrorException('user is verify');
        }

        if ($user->token === request()->get('token')) {
            $user->verified_at = now();
            $user->save();
        } else {
            return throw new ErrorException('token is not find');
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request): void
    {
        $request->validate([
            'name' => ['required', 'max:155'],
            'email' => ['required','email', 'max:255' ],
            'device_name' => 'required',
            'country_id' => ['required', 'integer']
        ]);

        $token = Str::random('32');

        $param = [
            'name' => $request->post('name'),
            'email' => $request->post('email'),
            'country_id' => $request->post('country_id'),
            'token' => $token,
        ];

        $user = User::create($param);
        $user->createToken($request->post('device_name'));

        Mail::to($user->email)->queue(new VerifiedMail($user, $token));
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function show(UsersFilter $filters): \Illuminate\Database\Eloquent\Collection
    {
        return User::filter($filters)->get();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     */
    public function update(Request $request, int $id): void
    {
        User::where('id', $id)->update($request->post());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     */
    public function destroy(int $id): void
    {
        User::destroy($id);
    }
}
