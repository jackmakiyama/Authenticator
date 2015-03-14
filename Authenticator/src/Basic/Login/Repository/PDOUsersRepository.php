<?php

namespace Basic\Login\Repository;

use Basic\Login\Entities\Users;
use PDO;

class PDOUsersRepository implements UsersRepository
{
    private $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function findByUsersname($username)
    {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE username = :username");
        $stmt->bindParam(":username", $username);
        $stmt->setFetchMode(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, '\\Basic\\Login\\Entities\\Users', ['', '']);
        $stmt->execute();

        $record = $stmt->fetch();

        if ($record->getUsername() !== $username) {
            return false;
        }

        return $record;
    }
}
