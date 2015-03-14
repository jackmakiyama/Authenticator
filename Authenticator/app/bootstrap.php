<?php

require_once __DIR__ . "/../vendor/autoload.php";

use Pimple\Container;
use Basic\Login\Repository\ArrayUsersRepository;
use Basic\Login\Authenticator;
use PHPFluent\ArrayStorage\Storage;
use Basic\Login\Repository\PDOUsersRepository;
use Respect\Relational\Mapper;
use Basic\Login\Repository\RespectUsersRepository;
use Basic\Login\Repository\CSVUsersRepository;
use League\Csv\Reader;

$container = new Container();

$container["array_storage"] = function($c) {
    $storage = new Storage();
    $storage->users->insert([
        'username' => 'john.doe',
        'password' => '$2y$10$R8E3yIfyjBrTXwq/c8F54e..sUHIx2THoZhvEg45ddC58eA2LnE46'
    ]);

    return $storage;
};

$container["sqlite_storage"] = function ($c) {
    $db = new \PDO('sqlite::memory:');
    $db->exec("
        CREATE TABLE users (
            id INTEGER PRIMARY KEY,
            username TEXT,
            password TEXT
        )
    ");

    $insert = '
        INSERT INTO users (username, password)
        VALUES (\'john.doe\', \'$2y$10$R8E3yIfyjBrTXwq/c8F54e..sUHIx2THoZhvEg45ddC58eA2LnE46\')
    ';

    $stmt = $db->prepare($insert);
    $stmt->execute();

    return $db;
};

$container["csv_storage"] = function($c) {
    $csv = Reader::createFromPath(new \SplFileObject(__DIR__ . '/data/users.csv'));
    $csv->setDelimiter(';');

    return $csv;
};

$container["array_user_repository"] = function ($c) {
    return new ArrayUsersRepository($c["array_storage"]);
};

$container["pdo_user_repository"] = function ($c) {
    return new PDOUsersRepository($c["sqlite_storage"]);
};

$container["respect_user_repository"] = function ($c) {
    return new RespectUsersRepository(new Mapper($c["sqlite_storage"]));
};

$container["csv_user_repository"] = function ($c) {
    return new CSVUsersRepository($c["csv_storage"]);
};

$container["authenticator"] = function ($c) {
    return new Authenticator($c["user_repository"]);
};

return $container;
