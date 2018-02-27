<?php

/******************************************************************************
 *
 * List
 *
 ******************************************************************************/

function characterList()
{
  $page = getUrlID('page', 1);
  $spells = getSpellPager($page);

  $template = new ListTemplate('Characters');
  $template->addCssFilePath('/themes/default/css/character.css');

  // Operations.
  $attr = array(
    'href' => 'character',
  );
  $template->addOperation(htmlWrap('a', 'New Character', $attr));

  if ($page > 1)
  {
    $attr = array(
      'href' => '?page=' . ($page - 1),
    );
    $template->addOperation(htmlWrap('a', 'Prev Page', $attr));
  }

  if (count($spells) >= DEFAULT_PAGER_SIZE)
  {
    $attr = array(
      'href' => '?page=' . ($page + 1),
    );
    $template->addOperation(htmlWrap('a', 'Next Page', $attr));
  }

  // List
  $table = new TableTemplate();
  $table->setAttr('class', array('character-list'));
  $table->addHeader(array('Name', 'Class', 'Subclass', 'Level', 'Race', 'Background', 'Player', 'Ops'));

  $characters = getCharacterPager($page);
  $classes = getClassList();
  $subclasses = getSubclassList();
  $races = getRaceList();
  $players = getPlayerList();
  foreach ($characters as $character)
  {
    $row = array();
    $attr = array(
      'href' => '/character?id=' . $character['id'],
    );
    $row[] = htmlWrap('a', $character['name'], $attr);

    $character_classes = getCharacterClasses($character['id']);
    $class = [];
    $subclass = [];
    $level = [];
    foreach ($character_classes as $character_class)
    {
      $class[] = $classes[$character_class['class_id']];
      $subclass[] = $subclasses[$character_class['subclass_id']];
      $level[] = $character_class['level'];
    }
    $row[] = join('/', $class);
    $row[] = join('/', $subclass);
    $row[] = join('/', $level);
    $row[] = $races[$character['race_id']];
    $row[] = $character['background'];
    $row[] = $players[$character['player_id']];

    $attr = array(
      'href' => 'character/print?id=' . $character['id'],
    );
    $row[] = htmlWrap('a', 'Print', $attr);
    $table->addRow($row);
  }
  $template->setList($table);
  return $template;
}