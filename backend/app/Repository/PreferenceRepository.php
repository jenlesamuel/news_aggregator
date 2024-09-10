<?php

namespace App\Repository;
use App\Models\Article;
use App\Models\User;

class PreferenceRepository 
{   
    public function getPreferences(User $user)
    {
        return $user->preferences;
    }

    public function updateOrCreate(User $user, array $preferences)
    {   
        return $user->preferences()->updateOrCreate(
            ['user_id' => $user->id], $preferences
        );
    }
}