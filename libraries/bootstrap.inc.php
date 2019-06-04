<?php
DEFINE('ROOT_PATH', __DIR__ . '/..');

// Include the platform file.
$platform_path = ROOT_PATH . '/platform.inc.php';
if (!file_exists($platform_path))
{
  die('platform.inc.php is missing.');
}
include $platform_path;

/****************
 * Libraries.
 ****************/

include ROOT_PATH . '/libraries/global.inc.php';
include ROOT_PATH . '/libraries/database/database.inc.php';
include ROOT_PATH . '/libraries/database/sqlite.inc.php';
include ROOT_PATH . '/libraries/form.inc.php';
include ROOT_PATH . '/libraries/template.inc.php';

include ROOT_PATH . '/libraries/session/session.inc.php';
include ROOT_PATH . '/libraries/session/session.db.php';

include ROOT_PATH . '/libraries/user/user.inc.php';
include ROOT_PATH . '/libraries/user/user.db.php';
include ROOT_PATH . '/libraries/user/user.pg.php';

/****************
 * Modules.
 ****************/

include ROOT_PATH . '/modules/attribute/attribute.db.php';
include ROOT_PATH . '/modules/attribute/attribute.pg.php';

include ROOT_PATH . '/modules/background/background.db.php';

include ROOT_PATH . '/modules/character/character.db.php';
include ROOT_PATH . '/modules/character/character.inc.php';
include ROOT_PATH . '/modules/character/character.pg.php';

include ROOT_PATH . '/modules/class/class.db.php';
include ROOT_PATH . '/modules/class/class.inc.php';
include ROOT_PATH . '/modules/class/class.pg.php';

include ROOT_PATH . '/modules/damage_type/damage_type.db.php';
include ROOT_PATH . '/modules/damage_type/damage_type.pg.php';

include ROOT_PATH . '/modules/item/item.db.php';
include ROOT_PATH . '/modules/item/item.pg.php';

include ROOT_PATH . '/modules/item_type/item_type.db.php';
include ROOT_PATH . '/modules/item_type/item_type.pg.php';

include ROOT_PATH . '/modules/player/player.db.php';
include ROOT_PATH . '/modules/player/player.pg.php';

include ROOT_PATH . '/modules/race/race.db.php';
include ROOT_PATH . '/modules/race/race.pg.php';

include ROOT_PATH . '/modules/skill/skill.db.php';
include ROOT_PATH . '/modules/skill/skill.pg.php';

include ROOT_PATH . '/modules/spell/spell.inc.php';
include ROOT_PATH . '/modules/spell/spell.db.php';
include ROOT_PATH . '/modules/spell/spell.pg.php';

