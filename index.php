<?php
include '/libraries/bootstrap.inc.php';

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
    'character/print' => 'characterPrint',
    'characters' => 'characterList',
    'character/attribute' => 'characterAttributeUpsertForm',
    'character/class' => 'characterClassUpsertForm',
    'character/skill' => 'characterSkillUpsertForm',
    'class' => 'classUpsertForm',
    'classes' => 'classList',
    'item' => 'itemUpsertForm',
    'items' => 'itemList',
    'items/print' => 'itemPrintForm',
    'language' => 'languageUpsertForm',
    'languages' => 'languageList',
    'player' => 'playerUpsertForm',
    'players' => 'playerList',
    'race' => 'raceUpsertForm',
    'races' => 'raceList',
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

  $output .= htmlWrap('a', 'Characters', array('href' => '/characters'));
  $output .= htmlWrap('a', 'Items', array('href' => '/items'));
  $output .= htmlWrap('a', 'Spells', array('href' => '/spells'));

  $output .= htmlWrap('a', '-');

  $output .= htmlWrap('a', 'Attributes', array('href' => '/attributes'));
  $output .= htmlWrap('a', 'Classes', array('href' => '/classes'));
  $output .= htmlWrap('a', 'Languages', array('href' => '/languages'));
  $output .= htmlWrap('a', 'Players', array('href' => '/players'));
  $output .= htmlWrap('a', 'Races', array('href' => '/races'));
  $output .= htmlWrap('a', 'Scripts', array('href' => '/scripts'));
  $output .= htmlWrap('a', 'Skills', array('href' => '/skills'));
  $output .= htmlWrap('a', 'Sources', array('href' => '/sources'));

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
