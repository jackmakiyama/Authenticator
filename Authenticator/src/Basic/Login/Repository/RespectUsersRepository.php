<?php

namespace Basic\Login\Repository;

use Basic\Login\Entities\Users;
use Respect\Relational\Mapper;

class RespectUsersRepository implements UsersRepository
{
    private $mapper;

    public function __construct(Mapper $mapper)
    {
        $this->mapper = $mapper;
        $this->mapper->entityNamespace = '\\Basic\\Login\\Entities\\';
    }

    public function findByUsersname($username)
    {
        $record = $this->mapper->Users(["username" => $username])->fetch();

        if ($record->getUsername() !== $username) {
            return false;
        }

        return $record;
    }
}

