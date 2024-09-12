<?php

namespace App\Repository;
use App\Models\Article;
use App\Models\Preference;
use App\Models\User;

class PreferenceRepository 
{   
    public function getPreference(User $user):?Preference
    {
        return $user->preference;
    }

    public function updateOrCreate(User $user, array $preference)
    {   
        return $user->preference()->updateOrCreate(
            ['user_id' => $user->id], $preference
        );
    }
}