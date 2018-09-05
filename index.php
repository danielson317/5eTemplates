<?php
include 'libraries/bootstrap.inc.php';

//echo menu();

// Retrieve body.
$url = new URL();
$function = getRegistry($url->getPath());
echo $function();


/**
 * @param bool|FALSE $path
 * @return array|string
 */
function getRegistry($path = FALSE)
{
  $registry = array(
    '/' => 'home',
    'unknown' => 'unknown',
    'ajax/subclass' => 'subclassAjax',
    'ajax/subrace' => 'subraceAjax',
    'attribute' => 'attributeUpsertForm',
    'attributes' => 'attributeList',
    'character' => 'characterUpsertForm',
    'character/attribute' => 'characterAttributeUpsertForm',
    'character/class' => 'characterClassUpsertForm',
    'character/language' => 'characterLanguageUpsertForm',
    'character/print' => 'characterPrint',
    'character/skill' => 'characterSkillUpsertForm',
    'characters' => 'characterList',
    'class' => 'classUpsertForm',
    'classes' => 'classList',
    'damage-types' => 'damageTypeList',
    'damage-type' => 'damageTypeUpsertForm',
    'item' => 'itemUpsertForm',
    'items' => 'itemList',
    'items/print' => 'itemPrintForm',
    'item-type' => 'itemTypeUpsertForm',
    'item-types' => 'itemTypeList',
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
    'script' => 'scriptUpsertForm',
    'scripts' => 'scriptList',
    'skill' => 'skillUpsertForm',
    'skills' => 'skillList',
    'spell' => 'spellUpsertForm',
    'spells' => 'spellList',
    'spells/print' => 'spellPrintForm',
    'source' => 'sourceUpsertForm',
    'sources' => 'sourceList',
    'subrace' => 'subraceUpsertForm',
    'subraces' => 'subraceList',
    'subclass' => 'subclassUpsertForm',
    'subclasses' => 'subclassList',
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
  $output .= htmlWrap('a', 'Characters', array('href' => '/characters'));
  $submenu = new ListTemplate('ul');
  $submenu->addListItem(htmlWrap('a', 'Attributes', array('href' => '/attributes')));
  $submenu->addListItem(htmlWrap('a', 'Classes', array('href' => '/classes')));
  $submenu->addListItem(htmlWrap('a', 'Languages', array('href' => '/languages')));
  $submenu->addListItem(htmlWrap('a', 'Races', array('href' => '/races')));
  $submenu->addListItem(htmlWrap('a', 'Scripts', array('href' => '/scripts')));
  $submenu->addListItem(htmlWrap('a', 'Skills', array('href' => '/skills')));
  $output .= $submenu;

  // Items.
  $output .= htmlWrap('a', 'Items', array('href' => '/items'));
  $submenu = new ListTemplate('ul');
  $submenu->addListItem(htmlWrap('a', 'Damage Types', array('href' => '/damage-types')));
  $submenu->addListItem(htmlWrap('a', 'Item Types', array('href' => '/item-types')));
  $submenu->addListItem(htmlWrap('a', 'Properties', array('href' => '/properties')));
  $submenu->addListItem(htmlWrap('a', 'Rarities', array('href' => '/rarities')));
  $output .= $submenu;

  // Monsters.
  $output .= htmlWrap('a', 'Monsters', array('href' => '/monsters'));

  // Spells.
  $output .= htmlWrap('a', 'Spells', array('href' => '/spells'));
  $submenu = new ListTemplate('ul');
  $submenu->addListItem(htmlWrap('a', 'Ranges', array('href' => '/ranges')));
  $output .= $submenu;

  // Other.
  $output .= htmlWrap('a', 'Other', array('href' => '/players'));
  $submenu = new ListTemplate('ul');
  $submenu->addListItem(htmlWrap('a', 'Sources', array('href' => '/sources')));
  $submenu->addListItem(htmlWrap('a', 'Players', array('href' => '/players')));
  $output .= $submenu;

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
