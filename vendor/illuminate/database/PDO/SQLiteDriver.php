<?php

namespace CBXWPBookmarkScoped\Illuminate\Database\PDO;

use CBXWPBookmarkScoped\Doctrine\DBAL\Driver\AbstractSQLiteDriver;
use CBXWPBookmarkScoped\Illuminate\Database\PDO\Concerns\ConnectsToDatabase;
class SQLiteDriver extends AbstractSQLiteDriver
{
    use ConnectsToDatabase;
}
