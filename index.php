<?php
include '/libraries/bootstrap.inc.php';

//echo menu();

// Retrieve body.
$url = new URL();
//debugPrint($url->getPath(), 'path', FALSE);
//debugPrint($url->getQuery(), 'query');
$function = getRegistry($url->getPath());
//echo $function;
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
    'character' => 'characterUpsertForm',
    'character/print' => 'characterPrint',
    'characters' => 'characterList',
    'item' => 'itemUpsertForm',
    'items' => 'itemList',
    'items/print' => 'itemPrintForm',
    'spell' => 'spellUpsertForm',
    'spells' => 'spellList',
    'spells/print' => 'spellPrintForm',
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
