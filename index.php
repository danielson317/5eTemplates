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
    'attribute' => 'attributeUpsertForm',
    'attributes' => 'attributeList',
    'character' => 'characterUpsertForm',
    'character/print' => 'characterPrint',
    'characters' => 'characterList',
    'character/class' => 'characterClassUpsertForm',
    'character/attribute' => 'characterAttributeUpsertForm',
    'classes' => 'classList',
    'class' => 'classUpsertForm',
    'item' => 'itemUpsertForm',
    'items' => 'itemList',
    'items/print' => 'itemPrintForm',
    'player' => 'playerUpsertForm',
    'players' => 'playerList',
    'skill' => 'skillUpsertForm',
    'skills' => 'skillList',
    'spell' => 'spellUpsertForm',
    'spells' => 'spellList',
    'spells/print' => 'spellPrintForm',
    'sources' => 'sourceList',
    'source' => 'sourceUpsertForm',
    'subclasses' => 'subclassList',
    'subclass' => 'subclassUpsertForm',
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

  $attr = array(
    'href' => '/characters',
  );
  $output .= htmlWrap('a', 'Characters', $attr);

  $attr = array(
    'href' => '/items',
  );
  $output .= htmlWrap('a', 'Items', $attr);

  $attr = array(
    'href' => '/spells',
  );
  $output .= htmlWrap('a', 'Spells', $attr);

  $output .= htmlWrap('a', '-');

  $attr = array(
    'href' => '/attributes',
  );
  $output .= htmlWrap('a', 'Attributes', $attr);

  $attr = array(
    'href' => '/classes',
  );
  $output .= htmlWrap('a', 'Classes', $attr);

  $attr = array(
    'href' => '/players',
  );
  $output .= htmlWrap('a', 'Players', $attr);

  $attr = array(
    'href' => '/skills',
  );
  $output .= htmlWrap('a', 'Skills', $attr);

  $attr = array(
    'href' => '/sources',
  );
  $output .= htmlWrap('a', 'Sources', $attr);

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
