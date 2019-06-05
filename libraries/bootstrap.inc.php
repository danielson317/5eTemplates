<?php
DEFINE('ROOT_PATH', __DIR__ . '/..');

// Include the platform file.
$platform_path = ROOT_PATH . '/platform.inc.php';
if (!file_exists($platform_path))
{
  die('platform.inc.php is missing.');
}
include $platform_path;

/*******************************
 * Environment settings.
 *******************************/
// Debug Flag. Set to FALSE for production. When true errorsPrint will display
// in the messages and debug statements will actually execute.
if (DEBUG)
{
  assert_options(ASSERT_ACTIVE,   TRUE);
  assert_options(ASSERT_BAIL,     TRUE);
  assert_options(ASSERT_WARNING,  TRUE);
  assert_options(ASSERT_CALLBACK, 'assertFailure');
  function errorHandler($severity, $message, $file, $line)
  {
    assert(FALSE, $message);
  }

  set_error_handler('errorHandler');
}
else
{
  assert_options(ASSERT_ACTIVE,   FALSE);
}

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

include ROOT_PATH . '/modules/aoe/aoe.db.php';
include ROOT_PATH . '/modules/aoe/aoe.pg.php';

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

include ROOT_PATH . '/modules/die/die.db.php';
include ROOT_PATH . '/modules/die/die.pg.php';

include ROOT_PATH . '/modules/item/item.db.php';
include ROOT_PATH . '/modules/item/item.pg.php';

include ROOT_PATH . '/modules/item_type/item_type.db.php';
include ROOT_PATH . '/modules/item_type/item_type.pg.php';

include ROOT_PATH . '/modules/language/language.db.php';
include ROOT_PATH . '/modules/language/language.pg.php';

include ROOT_PATH . '/modules/player/player.db.php';
include ROOT_PATH . '/modules/player/player.pg.php';

include ROOT_PATH . '/modules/race/race.db.php';
include ROOT_PATH . '/modules/race/race.pg.php';

include ROOT_PATH . '/modules/range/range.db.php';
include ROOT_PATH . '/modules/range/range.pg.php';

include ROOT_PATH . '/modules/school/school.db.php';
include ROOT_PATH . '/modules/school/school.pg.php';

include ROOT_PATH . '/modules/script/script.db.php';
include ROOT_PATH . '/modules/script/script.pg.php';

include ROOT_PATH . '/modules/skill/skill.db.php';
include ROOT_PATH . '/modules/skill/skill.pg.php';

include ROOT_PATH . '/modules/source/source.db.php';
include ROOT_PATH . '/modules/source/source.pg.php';

include ROOT_PATH . '/modules/speed/speed.db.php';
include ROOT_PATH . '/modules/speed/speed.pg.php';

include ROOT_PATH . '/modules/spell/spell.inc.php';
include ROOT_PATH . '/modules/spell/spell.db.php';
include ROOT_PATH . '/modules/spell/spell.pg.php';

include ROOT_PATH . '/modules/subclass/subclass.db.php';
include ROOT_PATH . '/modules/subclass/subclass.pg.php';

include ROOT_PATH . '/modules/subrace/subrace.db.php';
include ROOT_PATH . '/modules/subrace/subrace.pg.php';
