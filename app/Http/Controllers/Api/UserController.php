<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Filters\UsersFilter;
use App\Http\Requests\User\UserStoreRequest;
use App\Http\Requests\User\UserUpdateRequest;
use App\Http\Resources\UserResource;
use App\Mail\VerifiedMail;
use App\Models\User;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Mail;
use Psy\Exception\ErrorException;

class UserController extends Controller
{

    /**
     * Store a newly created resource in storage.
     *
     * @param UserStoreRequest $request
     */
    public function store(UserStoreRequest $request): void
    {
        $user = User::create($request->all());

        Mail::to($user->email)->queue(new VerifiedMail($user, $request->tokenСlean));
    }

    /**
     * Display the specified resource.
     *
     * @param UsersFilter $filters
     * @return AnonymousResourceCollection
     */
    public function show(UsersFilter $filters): AnonymousResourceCollection
    {
        return UserResource::collection(User::filter($filters)->get());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UserUpdateRequest $request
     * @param User $user
     */
    public function update(UserUpdateRequest $request, User $user): void
    {
        $user->update($request->post());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param User $user
     * @throws ErrorException
     */
    public function destroy(User $user): void
    {
        $user->authorLabels()->each(function ($label) {
            $label->projects()->detach();
            $label->delete();
        });

        $user->authorProjects()->each(function ($project) {
            $project->labels()->detach();
            $project->users()->detach();
            $project->delete();
        });
        $user->projects()->detach();

        $user->delete();
    }
}
