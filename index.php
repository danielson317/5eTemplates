<?php
include 'libraries/bootstrap.inc.php';

// Database.
if (!file_exists(DB_PATH))
{
  die('Database file does not exist. Visit /install.php to create a new one.');
}
if (!is_writable(dirname(DB_PATH)))
{
  die('Database file is not writable. Edit the file permission to give apache read/write access.');
}
GLOBAL $db;
$db = new SQLite(DB_PATH);

// Path.
GLOBAL $url;
$url = new URL();
$path = $url->getPath();

// Login.
session_name('dphdnd');
session_start();

GLOBAL $logged_in_user;
if (!$logged_in_user && $path !== 'unknown')
{
  $path = 'login';
}

if ($path === '')
{
  $path = '/';
}

// Retrieve body.
$function = getRegistry($path);
echo $function();

/******************************************************************************
 *
 * Core functions.
 *
 ******************************************************************************/

/**
 * @param bool|FALSE $path
 * @return array|string
 */
function getRegistry($path = FALSE)
{
  $registry = array(
    // Global.
    '/' => 'home',
    'unknown' => 'unknown',

    // Ajax.
    'ajax/character/ability' => 'characterabilityUpsertFormAjax',
    'ajax/character/class' => 'characterClassUpsertFormAjax',
    'ajax/character/language' => 'characterLanguageUpsertFormAjax',
    'ajax/character/item-proficiency' => 'characterItemProficiencyUpsertFormAjax',
    'ajax/character/item-type-proficiency' => 'characterItemTypeProficiencyUpsertFormAjax',
    'ajax/character/proficiencies' => 'characterItemTypeProficiencyAjax',
    'ajax/character/skill' => 'characterSkillUpsertFormAjax',
    'ajax/item/autocomplete' => 'getItemAutocompleteAjax',
    'ajax/item/damage' => 'itemDamageUpsertFormAjax',
    'ajax/subclass' => 'subclassAjax',
    'ajax/subrace' => 'subraceAjax',

    // Modules.
    'aoe' => 'aoeUpsertForm',
    'aoes' => 'aoeList',

    'ability' => 'abilityUpsertForm',
    'abilities' => 'abilityList',

    'background' => 'backgroundUpsertForm',
    'backgrounds' => 'backgroundList',

    'character' => 'characterUpsertForm',
    'character/print' => 'characterPrint',
    'characters' => 'characterList',

    'character/wizard/race' => 'characterWizardRaceForm',
    'character/wizard/class' => 'characterWizardClassForm',
    'character/wizard/ability' => 'characterWizardAbilityForm',
    'character/wizard/skill' => 'characterWizardSkillForm',
    'character/wizard/proficiency' => 'characterWizardProficiencyForm',
    'character/wizard/personality' => 'characterWizardPersonalityForm',
    'character/wizard/equipment' => 'characterWizardEquipmentForm',
    'character/wizard/spell' => 'characterWizardSpellForm',

    'class' => 'classUpsertForm',
    'classes' => 'classList',

    'damage-type' => 'damageTypeUpsertForm',
    'damage-types' => 'damageTypeList',

    'die' => 'dieUpsertForm',
    'dice' => 'dieList',

    'item' => 'itemUpsertForm',
    'items' => 'itemList',
    'items/print' => 'itemPrintForm',

    'language' => 'languageUpsertForm',
    'languages' => 'languageList',

    'player' => 'playerUpsertForm',
    'players' => 'playerList',

    'properties' => 'propertyList',
    'property' => 'propertyUpsertForm',

    'race' => 'raceUpsertForm',
    'races' => 'raceList',

    'range' => 'rangeUpsertForm',
    'ranges' => 'rangeList',

    'rarities' => 'rarityList',
    'rarity' => 'rarityUpsertForm',

    'school' => 'schoolUpsertForm',
    'schools' => 'schoolList',

    'script' => 'scriptUpsertForm',
    'scripts' => 'scriptList',

    'skill' => 'skillUpsertForm',
    'skills' => 'skillList',

    'source' => 'sourceUpsertForm',
    'sources' => 'sourceList',

    'speed' => 'speedUpsertForm',
    'speeds' => 'speedList',

    'spell' => 'spellUpsertForm',
    'spells' => 'spellList',
    'spells/print' => 'spellPrintForm',

    'subrace' => 'subraceUpsertForm',
    'subraces' => 'subraceList',

    'subclass' => 'subclassUpsertForm',
    'subclasses' => 'subclassList',

    // Users.
    'user' => 'userUpsertForm',
    'users' => 'userListPage',

    'login' => 'userLoginForm',
    'logout' => 'userLogout',
  );

  if ($path)
  {
    if (!isset($registry[$path]))
    {
      return $registry['unknown'];
    }
    return $registry[$path];
  }
  return $registry;
}

function menu()
{
  $output = '';

  // Characters
  $output .= a('Characters', '/characters');
  $submenu = new ListTemplate('ul');
  $submenu->addListItem(a('Characters', '/characters'));
  $submenu->addListItem(a('Wizard', '/character/wizard/race'));
  $submenu->addListItem(a('Abilities', '/abilities'));
  $submenu->addListItem(a('Classes', '/classes'));
  $submenu->addListItem(a('Languages', '/languages'));
  $submenu->addListItem(a('Races', '/races'));
  $submenu->addListItem(a('Scripts', '/scripts'));
  $submenu->addListItem(a('Skills', '/skills'));
  $output .= $submenu;

  // Items.
  $output .= a('Items', '/items');
  $submenu = new ListTemplate('ul');
  $submenu->addListItem(a('Damage Types', '/damage-types'));
  $submenu->addListItem(a('Properties', '/properties'));
  $submenu->addListItem(a('Rarities', '/rarities'));
  $output .= $submenu;

  // Monsters.
  $output .= a('Monsters', '/monsters');

  // Spells.
  $output .= a('Spells', '/spells');
  $submenu = new ListTemplate('ul');
  $submenu->addListItem(a('AOEs', '/aoes'));
  $submenu->addListItem(a('Ranges', '/ranges'));
  $submenu->addListItem(a('Schools', '/schools'));
  $submenu->addListItem(a('Speeds', '/speeds'));
  $output .= $submenu;

  // Other.
  $output .= a('Other', '/players');
  $submenu = new ListTemplate('ul');
  $submenu->addListItem(a('Sources', '/sources'));
  $submenu->addListItem(a('Players', '/players'));
  $submenu->addListItem(a('Dice', '/dice'));
  $output .= $submenu;

  // Users.
  GLOBAL $logged_in_user;
  $output .= a(sanitize($logged_in_user['username']), '/user', array('query' => array('user_id', $logged_in_user['id'])));
  $submenu = new ListTemplate('ul');
  $submenu->addListItem(a('Users', '/users'));
  $output .= $submenu;

  // Menu wrapper.
  $attr = array('id' => 'menu', 'class' => array('menu'));
  $output = htmlWrap('div', $output, $attr);
  return $output;
}

function home()
{
  $template = new HTMLTemplate();
  $template->setTitle('D\'s D&D DB');
  $template->setBody(menu() . htmlWrap('h1', 'Welcome to Daniel\'s Dungeons and Dragons Database'));

  echo $template;
}

function unknown()
{
  header("HTTP/1.1 404 Not Found");

  $template = new HTMLTemplate();
  $template->setTitle('dnd');
  $template->setBody(menu() . htmlWrap('h1', 'Page Not Found'));

  echo $template;
}
