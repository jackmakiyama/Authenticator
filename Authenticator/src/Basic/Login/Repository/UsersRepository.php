<?php

namespace Basic\Login\Repository;

interface UsersRepository
{
    public function findByUsersname($username);
}
