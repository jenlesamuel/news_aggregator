<?php 

namespace App\Services;
use App\Models\User;
use App\Repository\UserRepository;

class UserService
{   
    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function registerUser(array $userData): User {
        return $this->userRepository->create($userData);
    }
}