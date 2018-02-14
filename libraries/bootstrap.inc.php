<?php
DEFINE('ROOT_PATH', __DIR__ . '/..');

include ROOT_PATH . '/libraries/global.inc.php';
include ROOT_PATH . '/libraries/sqlite.inc.php';
include ROOT_PATH . '/libraries/form.inc.php';

//include ROOT_PATH . '/modules/spell/spell.inc.php';

include ROOT_PATH . '/modules/item/item.inc.php';
include ROOT_PATH . '/modules/item/item.db.php';

GLOBAL $db;
$db = new SQLite('C:\Users\DanielPHenry\Dropbox\Gaming\D&D5e\dnd.db');
