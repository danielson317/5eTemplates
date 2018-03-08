<?php
DEFINE('ROOT_PATH', __DIR__ . '/..');

include ROOT_PATH . '/libraries/global.inc.php';
include ROOT_PATH . '/libraries/sqlite.inc.php';
include ROOT_PATH . '/libraries/form.inc.php';
include ROOT_PATH . '/libraries/template.inc.php';

include ROOT_PATH . '/modules/attributes/attributes.db.php';

include ROOT_PATH . '/modules/character/character.db.php';
include ROOT_PATH . '/modules/character/character.inc.php';
include ROOT_PATH . '/modules/character/character.pg.php';

include ROOT_PATH . '/modules/class/class.db.php';
include ROOT_PATH . '/modules/class/class.inc.php';

include ROOT_PATH . '/modules/item/item.db.php';
include ROOT_PATH . '/modules/item/item.pg.php';

include ROOT_PATH . '/modules/player/player.db.php';
include ROOT_PATH . '/modules/player/player.pg.php';

include ROOT_PATH . '/modules/race/race.db.php';

include ROOT_PATH . '/modules/spell/spell.inc.php';
include ROOT_PATH . '/modules/spell/spell.db.php';
include ROOT_PATH . '/modules/spell/spell.pg.php';

GLOBAL $db;
$db = new SQLite('C:\Users\DanielPHenry\Dropbox\Gaming\D&D5e\dnd.db');
