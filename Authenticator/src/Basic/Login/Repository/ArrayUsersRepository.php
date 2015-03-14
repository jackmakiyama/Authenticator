<?php

namespace Basic\Login\Repository;

use Basic\Login\Entities\Users;
use PHPFluent\ArrayStorage\Record;
use PHPFluent\ArrayStorage\Storage;

class ArrayUsersRepository implements UsersRepository
{
    private $users;

    public function __construct(Storage $storage)
    {
        $this->users = $storage->users;
    }

    public function findByUsersname($username)
    {
        $record = $this->users->find(array('username' => $username));

        if (!$record instanceof Record) {
            die;
            return false;
        }

        $user = new Users($record->username, $record->password);
        $user->id = $record->id;

        return $user;
    }
}
