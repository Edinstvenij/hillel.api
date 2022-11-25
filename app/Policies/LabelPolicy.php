<?php

namespace App\Policies;

use App\Models\Label;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class LabelPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can delete the model.
     *
     * @param \App\Models\User $user
     * @param \App\Models\Label $label
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Label $label)
    {
        return $user->id === $label->author_id
            ? Response::allow()
            : Response::deny('You are not the author');
    }
}
