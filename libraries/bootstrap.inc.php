<?php

DEFINE('ROOT_PATH', __DIR__ . '/..');

// Include the platform file.
$platform_path = ROOT_PATH . '/platform.inc.php';
if (!file_exists($platform_path))
{
  die('platform.inc.php is missing.');
}
include $platform_path;

date_default_timezone_set('America/Denver');

/*******************************
 * Environment settings.
 *******************************/
// Debug Flag. Set to FALSE for production. When true errorsPrint will display
// in the messages and debug statements will actually execute.
//if (DEBUG)
//{
//  assert_options(ASSERT_ACTIVE,   TRUE);
//  assert_options(ASSERT_BAIL,     TRUE);
//  assert_options(ASSERT_WARNING,  TRUE);
//  assert_options(ASSERT_CALLBACK, 'assertFailure');
//  function errorHandler($severity, $message, $file, $line)
//  {
//    assert(FALSE, $message);
//  }
//
//  set_error_handler('errorHandler');
//}
//else
//{
//  assert_options(ASSERT_ACTIVE,   FALSE);
//}

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

include ROOT_PATH . '/modules/ability/ability.db.php';
include ROOT_PATH . '/modules/ability/ability.pg.php';
include ROOT_PATH . '/modules/ability/skill.db.php';
include ROOT_PATH . '/modules/ability/skill.pg.php';

include ROOT_PATH . '/modules/background/background.db.php';
include ROOT_PATH . '/modules/background/background.pg.php';
include ROOT_PATH . '/modules/background/background_characteristic.db.php';

include ROOT_PATH . '/modules/character/character.db.php';
include ROOT_PATH . '/modules/character/character.inc.php';
include ROOT_PATH . '/modules/character/character.pg.php';
include ROOT_PATH . '/modules/character/character_wizard.pg.php';

include ROOT_PATH . '/modules/class/class.db.php';
include ROOT_PATH . '/modules/class/class.inc.php';
include ROOT_PATH . '/modules/class/class.pg.php';
include ROOT_PATH . '/modules/class/subclass.db.php';
include ROOT_PATH . '/modules/class/subclass.pg.php';

include ROOT_PATH . '/modules/item/item.db.php';
include ROOT_PATH . '/modules/item/item.inc.php';
include ROOT_PATH . '/modules/item/item.pg.php';

include ROOT_PATH . '/modules/language/language.db.php';
include ROOT_PATH . '/modules/language/language.pg.php';
include ROOT_PATH . '/modules/language/script.db.php';
include ROOT_PATH . '/modules/language/script.pg.php';

include ROOT_PATH . '/modules/player/player.db.php';
include ROOT_PATH . '/modules/player/player.pg.php';

include ROOT_PATH . '/modules/race/race.db.php';
include ROOT_PATH . '/modules/race/race.pg.php';
include ROOT_PATH . '/modules/race/subrace.db.php';
include ROOT_PATH . '/modules/race/subrace.pg.php';

include ROOT_PATH . '/modules/source/source.db.php';
include ROOT_PATH . '/modules/source/source.pg.php';

include ROOT_PATH . '/modules/spell/spell.inc.php';
include ROOT_PATH . '/modules/spell/spell.db.php';
include ROOT_PATH . '/modules/spell/spell.pg.php';
