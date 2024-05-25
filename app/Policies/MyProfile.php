<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class MyProfile
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the patient information page.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function viewPatientInformation(User $user)
    {
        return false;
    }
}
