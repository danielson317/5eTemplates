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

  $form = new Form('character_form');
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

  /*****************
   * Header Group
   *****************/
  $group = 'header';
  $form->addGroup($group);

  // Name.
  $field = new FieldText('name', 'Name');
  $field->setGroup($group)->setRequired();
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
    $field->setGroup($group);
    $form->addField($field);
  }

  // Race.
  $options = array(0 => '--Select One--') + getRaceList();
  $field = new FieldSelect('race_id', 'Race', $options);
  $field->setGroup($group);
  $field->setRequired();
  $form->addField($field);

  // Player.
  $options = array(0 => '--Select One--') + getPlayerList();
  $field = new FieldSelect('player_id', 'Player', $options);
  $field->setGroup($group);
  $field->setRequired();
  $form->addField($field);

  // Background.
  $field = new FieldText('background', 'Background');
  $field->setGroup($group);
  $form->addField($field);

  // XP.
  $field = new FieldNumber('xp', 'Experience Points');
  $field->setGroup($group);
  $form->addField($field);

  // Alignment.
  $options = array(0 => '--Select One--') + getAlignmentList();
  $field = new FieldSelect('alignment', 'Alignment', $options);
  $field->setGroup($group);
  $form->addField($field);

  /*****************
   * Stats Group
   *****************/
  $group = 'stats';
  $form->addGroup($group);

  // Max HP.
  $field = new FieldNumber('hp', 'Max HP');
  $field->setGroup($group);
  $form->addField($field);

  // Proficiency Bonus.
  $field = new FieldNumber('pb', 'Proficiency Bonus');
  $field->setValue(2);
  $field->setGroup($group);
  $form->addField($field);

  // Speed.
  $field = new FieldNumber('speed', 'Speed');
  $field->setValue(30);
  $field->setGroup($group);
  $form->addField($field);

  /*********************
   * Personality Group
   *********************/
  $group = 'personality';
  $form->addGroup($group);

  // Personality Traits.
  $field = new FieldTextarea('personality', 'Personality Traits');
  $field->setAttr('maxlength', '150')->setCols(30);
  $field->setGroup($group);
  $form->addField($field);

  // Ideals.
  $field = new FieldTextarea('ideals', 'Ideals');
  $field->setAttr('maxlength', '150')->setCols(30);
  $field->setGroup($group);
  $form->addField($field);

  // Bonds.
  $field = new FieldTextarea('bonds', 'Bonds');
  $field->setAttr('maxlength', '150')->setCols(30);
  $field->setGroup($group);
  $form->addField($field);

  // Flaws.
  $field = new FieldTextarea('flaws', 'Flaws');
  $field->setAttr('maxlength', '150')->setCols(30);
  $field->setGroup($group);
  $form->addField($field);

  // Features.
  $field = new FieldTextarea('features', 'Features');
  $field->setAttr('maxlength', '1000')->setRows(30)->setCols(30);
  $field->setGroup($group);
  $form->addField($field);

  /*********************
   * Operations Group
   *********************/
  $group = 'operations';
  $form->addGroup($group);

  // Submit
  $value = 'Create';
  if ($character_id)
  {
    $value = 'Update';
  }
  $field = new FieldSubmit('submit', $value);
  $field->setGroup($group);
  $form->addField($field);

  $template->setForm($form);

  return $template;
}

function characterUpsertSubmit()
{
  $character = $_POST;
  unset($character['submit']);

  if ($character['id'])
  {
    updateCharacter($character);
    return htmlWrap('h3', 'Character ' . htmlWrap('em', $character['name']) . ' (' . $character['id'] . ') updated.');
  }
  else
  {
    unset($character['id']);
    $character['id'] = createCharacter($character);
    return htmlWrap('h3', 'New character ' . htmlWrap('em', $character['name']) . ' (' . $character['id'] . ') created.');
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
  if ($class_id)
  {
    $character_class = getCharacterClass($character_id, $class_id);
    $form->setValues($character_class);
    $title = 'Edit character ' . htmlWrap('em', $character['name']) . '\'s class ' . htmlWrap('em', $classes[$character_class['class_id']]);

    $field = new FieldHidden('operation', 'update');
    $form->addField($field);
  }
  else
  {
    $title = 'Add New Class to ' . htmlWrap('em', $character['name']);

    $field = new FieldHidden('operation', 'create');
    $form->addField($field);
  }
  $form->setTitle($title);

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

  // Character ID.
  $field = new FieldHidden('character_id');
  $field->setValue($character_id);
  $form->addField($field);

  // Class.
  if (!$class_id)
  {
    $options = array(0 => '--Select One--') + $classes;
    foreach ($character_classes as $character_class)
    {
      unset($options[$character_class['class_id']]);
    }
    $field = new FieldSelect('class_id', 'Class', $options);
    $form->addField($field);
  }
  else
  {
    $field = new FieldHidden('class_id');
    $form->addField($field);
  }

  // Subclass.
  $options = array(0 => '--Select One--') + getSubclassList($class_id);
  $field = new FieldSelect('subclass_id', 'Subclass', $options);
  $field->setRequired();
  $form->addField($field);

  // Level.
  $field = new FieldNumber('level', 'Level');
  $form->addField($field);

  // Submit
  $value = 'Add';
  if ($class_id)
  {
    $value = 'Update';
  }
  $field = new FieldSubmit('submit', $value);
  $form->addField($field);

  $field = new FieldSubmit('delete', 'Delete');
  $form->addField($field);

  $template->setForm($form);

  return $template;
}

function characterClassUpsertSubmit()
{
  $character_class = array(
    'character_id' => $_POST['character_id'],
    'class_id' => $_POST['class_id'],
    'subclass_id' => $_POST['subclass_id'],
    'level' => $_POST['level'],
  );

  if (isset($_POST['delete']))
  {
    deleteCharacterClass($character_class);
    redirect('/character?id=' . $_POST['character_id']);
  }
  // Update.
  elseif ($_POST['operation'] == 'update')
  {
    updateCharacterClass($character_class);
    return htmlWrap('h3', 'Updated.');
  }
  // Create.
  else
  {
    createCharacterClass($character_class);
    return htmlWrap('h3', 'Created.');
  }
}
