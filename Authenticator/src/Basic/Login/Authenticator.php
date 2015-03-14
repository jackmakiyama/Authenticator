<?php

namespace Basic\Login;

use Basic\Login\Entities\Users;
use Basic\Login\Repository\UsersRepository;

class Authenticator
{
    protected $userRepository;

    public function __construct(UsersRepository $repository)
    {
        $this->userRepository = $repository;
    }

    public function authenticate($username, $password)
    {
        $userRepository = $this->userRepository;
        $user = $userRepository->findByUsersname($username);

        if (!$user instanceof Users) {
            return false;
        }

        if (!password_verify($password, $user->getPassword())) {
            die();
            return false;
        }

        return $user;
    }
}
