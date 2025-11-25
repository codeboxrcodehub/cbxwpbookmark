<?php

namespace CBXWPBookmarkScoped\Illuminate\Database\PDO;

use CBXWPBookmarkScoped\Doctrine\DBAL\Driver\AbstractPostgreSQLDriver;
use CBXWPBookmarkScoped\Illuminate\Database\PDO\Concerns\ConnectsToDatabase;
class PostgresDriver extends AbstractPostgreSQLDriver
{
    use ConnectsToDatabase;
}
