<?php

namespace Hasirciogli\Hdb;

use Hasirciogli\Hdb\Interfaces\Database\Config\DatabaseConfigInterface;

class MyDbConfig implements DatabaseConfigInterface
{
    const DB_HOST = "localhost";
    const DB_NAME = "db_name";
    const DB_USER = "db_user";
    const DB_PASS = "db_pass";
}