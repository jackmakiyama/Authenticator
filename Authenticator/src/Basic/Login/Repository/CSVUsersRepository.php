<?php

namespace Basic\Login\Repository;

use Basic\Login\Entities\Users;

class CSVUsersRepository implements UsersRepository
{
    private $csv;

    public function __construct($csv)
    {
        $this->csv = $csv;
    }

    public function findByUsersname($username)
    {
        $record = $this->csv
            ->addFilter(function ($row, $index) {
                return $index > 0;
            })
            ->addFilter(function ($row) {
                return isset($row[0], $row[1], $row[2]); //we make sure the data are present
            })
            ->addFilter(function ($row) use ($username) {
                return $username === $row[1]; //the name is used less than 10 times
            })->fetchOne(0);

        if ($record === []) {
            return false;
        }

        $user = new Users($record[1], $record[2]);
        $user->id = $record[0];

        return $user;
    }
}
