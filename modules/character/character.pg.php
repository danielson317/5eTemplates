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
  $table->setHeader(array('Name', 'Class', 'Subclass', 'Level', 'Race', 'Background', 'Player', 'Ops'));

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

/******************************************************************************
 *
 * Character Upsert
 *
 ******************************************************************************/

function characterUpsertForm()
{
  $template = new FormTemplate();
  $template->addCssFilePath('/themes/default/css/character.css');

  // Submit.
  if (isset($_SERVER['REQUEST_METHOD']) && ($_SERVER['REQUEST_METHOD'] == 'POST'))
  {
    $template->addMessage(characterUpsertSubmit());
  }

  $character_id = getUrlID('id');

  $form = new Form('spell_form');
  $title = 'Add New Character';
  if ($character_id)
  {
    $character = getCharacter($character_id);
    $form->setValues($character);
    $title = 'Edit character ' . htmlWrap('em', $character['name']);
  }
  $form->setTitle($title);

  // ID.
  $field = new FieldHidden('id');
  $form->addField($field);

  // Name.
  $field = new FieldText('name', 'Name');
  $form->addField($field);

  // Classes
  $classes = getClassList();
  $subclasses = getSubclassList();
  if ($character_id)
  {
    $table = new TableTemplate('character-class');
    $table->setHeader(array('Class', 'Subclass', 'Level'));
    $character_classes = getCharacterClasses($character_id);
    foreach ($character_classes as $character_class)
    {

      $row = array();
      $attr = array(
        'href' => '/character/class?character_id=' . $character_id . '&class_id=' . $character_class['class_id'],
      );
      $row[] = htmlWrap('a', $classes[$character_class['class_id']], $attr);
      $row[] = $subclasses[$character_class['subclass_id']];
      $row[] = $character_class['level'];
      $table->addRow($row);
    }

    $attr = array(
      'href' => '/character/class?character_id=' . $character_id,
    );
    $link = htmlWrap('a', 'Add New Class', $attr);

    $field = new FieldMarkup('classes', 'Classes', $table . $link);
    $form->addField($field);
  }

  // Race.
  $options = array(0 => '--Select One--') + getRaceList();
  $field = new FieldSelect('race_id', 'Race', $options);
  $field->setRequired();
  $form->addField($field);

  // Player.
  $options = array(0 => '--Select One--') + getPlayerList();
  $field = new FieldSelect('player_id', 'Player', $options);
  $field->setRequired();
  $form->addField($field);

  // Background.
  $field = new FieldText('background', 'Background');
  $form->addField($field);

  // Submit
  $value = 'Create';
  if ($character_id)
  {
    $value = 'Update';
  }
  $field = new FieldSubmit('submit', $value);
  $form->addField($field);

  $template->setForm($form);

  return $template;
}

function characterUpsertSubmit()
{
  $spell = $_POST;
  $spell['ritual'] = isset($_POST['ritual']) ? 1 : 0;
  $spell['concentration'] = isset($_POST['concentration']) ? 1 : 0;
  $spell['verbal'] = isset($_POST['verbal']) ? 1 : 0;
  $spell['semantic'] = isset($_POST['semantic']) ? 1 : 0;
  unset($spell['submit']);

  if ($spell['id'])
  {
    updateCharacter($spell);
    return htmlWrap('h3', 'Character ' . htmlWrap('em', $spell['name']) . ' (' . $spell['id'] . ') updated.');
  }
  else
  {
    unset($spell['id']);
    $spell['id'] = createCharacter($spell);
    return htmlWrap('h3', 'New character ' . htmlWrap('em', $spell['name']) . ' (' . $spell['id'] . ') created.');
  }
}

/******************************************************************************
 *
 * Character Class Upsert
 *
 ******************************************************************************/
function characterClassUpsertForm()
{
  $template = new FormTemplate();
  $template->addCssFilePath('/themes/default/css/character.css');

  // Submit.
  if (isset($_SERVER['REQUEST_METHOD']) && ($_SERVER['REQUEST_METHOD'] == 'POST'))
  {
    $template->addMessage(characterClassUpsertSubmit());
  }

  $character_id = getUrlID('character_id');
  if (!$character_id)
  {
    $template->addMessage('Missing parameter character_id.');
    return $template;
  }
  $character = getCharacter($character_id);
  $class_id = getUrlID('class_id');
  $classes = getClassList();
  $subclasses = getSubclassList();
  $character_classes = getCharacterClasses($character_id);

  $form = new Form('character_class_form');
  $title = 'Add New Class to ' . htmlWrap('em', $character['name']);
  if ($class_id)
  {
    $character_class = getCharacterClass($character_id, $class_id);
    $form->setValues($character_class);
    $title = 'Edit character ' . htmlWrap('em', $character['name']) . '\'s class ' . htmlWrap('em', $classes[$character_class['class_id']]);
  }
  $form->setTitle($title);

  // Character ID.
  $field = new FieldHidden('character_id');
  $form->addField($field);

  // Class List.
  $table = new TableTemplate('character-class');
  $table->setHeader(array('Class', 'Subclass', 'Level'));
  foreach ($character_classes as $character_class)
  {
    $row = array();
    $row[] = $classes[$character_class['class_id']];
    $row[] = $subclasses[$character_class['subclass_id']];
    $row[] = $character_class['level'];
    $table->addRow($row);
  }
  $attr = array(
    'href' => '/character?id=' . $character_id,
  );
  $link = htmlWrap('a', 'Back to ' . htmlWrap('em', $character['name']), $attr);

  $field = new FieldMarkup('classes', '', $table . $link);
  $form->addField($field);

  // Class.
  $options = $classes;
  foreach ($character_classes as $character_class)
  {
    unset($options[$character_class['class_id']]);
  }
  $field = new FieldSelect('class_id', 'Class', $options);
  $form->addField($field);

  $options = array(0 => '--Select One--') + getSubclassList($class_id);
  $field = new FieldSelect('subclass_id', 'Subclass', $options);
  $field->setRequired();
  $form->addField($field);

  // Level.
  $field = new FieldNumber('level', 'Level');
  $form->addField($field);

  // Submit
  $value = 'Create';
  if ($character_id)
  {
    $value = 'Update';
  }
  $field = new FieldSubmit('submit', $value);
  $form->addField($field);

  $template->setForm($form);

  return $template;
}

function characterClassUpsertSubmit()
{
  $spell = $_POST;
  $spell['ritual'] = isset($_POST['ritual']) ? 1 : 0;
  $spell['concentration'] = isset($_POST['concentration']) ? 1 : 0;
  $spell['verbal'] = isset($_POST['verbal']) ? 1 : 0;
  $spell['semantic'] = isset($_POST['semantic']) ? 1 : 0;
  unset($spell['submit']);

  if ($spell['id'])
  {
    updateCharacter($spell);
    return htmlWrap('h3', 'Character ' . htmlWrap('em', $spell['name']) . ' (' . $spell['id'] . ') updated.');
  }
  else
  {
    unset($spell['id']);
    $spell['id'] = createCharacter($spell);
    return htmlWrap('h3', 'New character ' . htmlWrap('em', $spell['name']) . ' (' . $spell['id'] . ') created.');
  }
}
