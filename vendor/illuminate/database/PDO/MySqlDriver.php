<?php

namespace CBXWPBookmarkScoped\Illuminate\Database\PDO;

use CBXWPBookmarkScoped\Doctrine\DBAL\Driver\AbstractMySQLDriver;
use CBXWPBookmarkScoped\Illuminate\Database\PDO\Concerns\ConnectsToDatabase;
class MySqlDriver extends AbstractMySQLDriver
{
    use ConnectsToDatabase;
}
