<?php

/******************************************************************************
 *
 * List
 *
 ******************************************************************************/

function characterList()
{
  $page = getUrlID('page', 1);
  $characters = getCharacterPager($page);

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

  if (count($characters) >= DEFAULT_PAGER_SIZE)
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

  // Background.
  $field = new FieldText('background', 'Background');
  $field->setGroup($group);
  $form->addField($field);

  // Player.
  $options = array(0 => '--Select One--') + getPlayerList();
  $field = new FieldSelect('player_id', 'Player', $options);
  $field->setGroup($group);
  $field->setRequired();
  $form->addField($field);

  // Race.
  $options = array(0 => '--Select One--') + getRaceList();
  $field = new FieldSelect('race_id', 'Race', $options);
  $field->setGroup($group);
  $field->setRequired();
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

  /********************
   * Attributes Group
   ********************/
  $group = 'attributes';
  $form->addGroup($group);

  $attributes = getAttributeList();
  $character_attributes = getCharacterAttributes($character_id);
  $table = new TableTemplate();
  $table->setHeader(array('Attr', 'Score', 'Mod', 'Prof', 'ST'));
  foreach($character_attributes as $character_attribute)
  {
    $row = array();
    $attr = array(
      'href' => '/character/attribute?character_id=' . $character_id . '&attribute_id=' . $character_attribute['attribute_id'],
    );
    $row[] = htmlWrap('a', $attributes[$character_attribute['attribute_id']], $attr);
    $row[] = $character_attribute['score'];
    $row[] = $character_attribute['modifier'];
    $row[] = $character_attribute['proficiency'];
    $row[] = $character_attribute['saving_throw'];
    $table->addRow($row);
  }

  $attr = array(
    'href' => '/character/attribute?character_id=' . $character_id,
  );
  $link = htmlWrap('a', 'Add New Attribute', $attr);

  $field = new FieldMarkup('attributes', 'Attributes', $table . $link);
  $field->setGroup($group);
  $form->addField($field);

  // Skills
  $skills = getSkillList();
  $character_skills = getCharacterSkills($character_id);
  $table = new TableTemplate();
  $table->setHeader(array('Skill', 'Prof', 'Mod'));
  foreach($character_skills as $character_skill)
  {
    $row = array();
    $attr = array(
      'href' => '/character/skill?character_id=' . $character_id . '&skill_id=' . $character_skill['skill_id'],
    );
    $row[] = htmlWrap('a', $skills[$character_skill['skill_id']], $attr);
    $row[] = $character_skill['proficiency'];
    $row[] = $character_skill['modifier'];
    $table->addRow($row);
  }

  $attr = array(
    'href' => '/character/skill?character_id=' . $character_id,
  );
  $link = htmlWrap('a', 'Add New Skill', $attr);

  $field = new FieldMarkup('skills', 'Skills', $table . $link);
  $field->setGroup($group);
  $form->addField($field);

  // Languages
  $languages = getLanguageList();
  $character_languages = getCharacterLanguages($character_id);

  $list = array();
  foreach($character_languages as $character_language)
  {
    $attr = array(
      'href' => '/character/language?character_id=' . $character_id . '&language_id=' . $character_language['language_id'],
    );
    $list[] = htmlWrap('a', $languages[$character_language['language_id']], $attr);
  }

  $attr = array(
    'href' => '/character/language?character_id=' . $character_id,
  );
  $link = htmlWrap('a', 'Add New Language', $attr);

  $field = new FieldMarkup('languages', '<none>', 'Languages: ' . implode(', ', $list) . '<br>' . $link);
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
  $template->addJsFilePath('/modules/character/character.js');

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
  if ($class_id)
  {
    $options = array(0 => '--Select One--') + getSubclassList($class_id);
  }
  else
  {
    $options = array(0 => 'Select a class first.');
  }
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
  $character_class = $_POST;
  unset($character_class['submit']);
  unset($character_class['operation']);

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

/******************************************************************************
 *
 * Character Attribute Upsert
 *
 ******************************************************************************/
function characterAttributeUpsertForm()
{
  $template = new FormTemplate();
  $template->addCssFilePath('/themes/default/css/character.css');
  $template->addJsFilePath('/modules/character/character.js');

  // Submit.
  if (isset($_SERVER['REQUEST_METHOD']) && ($_SERVER['REQUEST_METHOD'] == 'POST'))
  {
    $template->addMessage(characterAttributeUpsertSubmit());
  }

  $character_id = getUrlID('character_id');
  if (!$character_id)
  {
    $template->addMessage('Missing parameter character_id.');
    return $template;
  }
  $character = getCharacter($character_id);
  $attribute_id = getUrlID('attribute_id');
  $attributes = getAttributeList();
  $character_attributes = getCharacterAttributes($character_id);

  $form = new Form('character_attribute_form');
  if ($attribute_id)
  {
    $character_attribute = getCharacterAttribute($character_id, $attribute_id);
    $form->setValues($character_attribute);
    $title = 'Edit character ' . htmlWrap('em', $character['name']) . '\'s attribute ' . htmlWrap('em', $attributes[$character_attribute['attribute_id']]);

    $field = new FieldHidden('operation', 'update');
    $form->addField($field);
  }
  else
  {
    $title = 'Add New Attribute to ' . htmlWrap('em', $character['name']);

    $field = new FieldHidden('operation', 'create');
    $form->addField($field);
  }
  $form->setTitle($title);

  $markup = htmlWrap('span', $character['pb'], array('class' => array('pb')));
  $field = new FieldMarkup('pb', 'Proficiency Bonus', $markup);
  $form->addField($field);

  // Attribute List.
  $table = new TableTemplate();
  $table->setHeader(array('Attr', 'Score', 'Mod', 'Prof', 'ST'));
  foreach($character_attributes as $character_attribute)
  {
    $row = array();
    $attr = array(
      'href' => '/character/attribute?character_id=' . $character_id . '&attribute_id=' . $character_attribute['attribute_id'],
    );
    $row[] = htmlWrap('a', $attributes[$character_attribute['attribute_id']], $attr);
    $row[] = $character_attribute['score'];
    $row[] = $character_attribute['modifier'];
    $row[] = $character_attribute['proficiency'];
    $row[] = $character_attribute['saving_throw'];
    $table->addRow($row);
  }

  $attr = array(
    'href' => '/character/attribute?character_id=' . $character_id,
  );
  $links = htmlWrap('a', 'Add New Attribute', $attr) . '<br>';

  $attr = array(
    'href' => '/character?id=' . $character_id,
  );
  $links .= htmlWrap('a', 'Back to ' . $character['name'], $attr);

  $field = new FieldMarkup('attributes', 'Attributes', $table . $links);
  $form->addField($field);

  // Character.
  $field = new FieldHidden('character_id');
  $field->setValue($character_id);
  $form->addField($field);

  // Attribute.
  if (!$attribute_id)
  {
    $options = $attributes;
    foreach ($character_attributes as $character_attribute)
    {
      unset($options[$character_attribute['attribute_id']]);
    }
    $field = new FieldSelect('attribute_id', 'Attribute', $options);
    $form->addField($field);
  }
  else
  {
    $field = new FieldHidden('attribute_id');
    $form->addField($field);
  }

  // Score.
  $field = new FieldNumber('score', 'Score');
  $field->setValue(8);
  $form->addField($field);

  // Modifier.
  $field = new FieldNumber('modifier', 'Modifier');
  $field->setValue(-1);
  $form->addField($field);

  // Proficiency.
  $field = new FieldNumber('proficiency', 'Saving Throw Proficiency Multiplier');
  $field->setValue(0);
  $form->addField($field);

  // Saving Throw.
  $field = new FieldNumber('saving_throw', 'Saving Throw');
  $field->setValue(-1);
  $form->addField($field);

  // Submit
  $value = 'Add';
  if ($attribute_id)
  {
    $value = 'Update';
  }
  $field = new FieldSubmit('submit', $value);
  $form->addField($field);

  if ($attribute_id)
  {
    $field = new FieldSubmit('delete', 'Delete');
    $form->addField($field);
  }

  $template->setForm($form);

  return $template;
}

function characterAttributeUpsertSubmit()
{
  $character_attribute = $_POST;
  unset($character_attribute['submit']);
  unset($character_attribute['operation']);

  if (isset($_POST['delete']))
  {
    deleteCharacterAttribute($character_attribute);
    redirect('/character?id=' . $character_attribute['character_id']);
  }
  // Update.
  elseif ($_POST['operation'] == 'update')
  {
    updateCharacterAttribute($character_attribute);
    return htmlWrap('h3', 'Updated.');
  }
  // Create.
  else
  {
    createCharacterAttribute($character_attribute);
    return htmlWrap('h3', 'Created.');
  }
}

/******************************************************************************
 *
 * Character Skill Upsert
 *
 ******************************************************************************/
function characterSkillUpsertForm()
{
  $template = new FormTemplate();
  $template->addCssFilePath('/themes/default/css/character.css');
  $template->addJsFilePath('/modules/character/character.js');

  // Submit.
  if (isset($_SERVER['REQUEST_METHOD']) && ($_SERVER['REQUEST_METHOD'] == 'POST'))
  {
    $template->addMessage(characterSkillUpsertSubmit());
  }

  $character_id = getUrlID('character_id');
  if (!$character_id)
  {
    $template->addMessage('Missing parameter character_id.');
    return $template;
  }
  $character = getCharacter($character_id);
  $skill_id = getUrlID('skill_id');
  $skills = getSkillList();
  $character_skills = getCharacterSkills($character_id);

  $form = new Form('character_skill_form');
  if ($skill_id)
  {
    $character_skill = getCharacterSkill($character_id, $skill_id);
    $form->setValues($character_skill);
    $title = 'Edit character ' . htmlWrap('em', $character['name']) . '\'s skill ' . htmlWrap('em', $skills[$character_skill['skill_id']]);

    $field = new FieldHidden('operation', 'update');
    $form->addField($field);
  }
  else
  {
    $title = 'Add New Skill to ' . htmlWrap('em', $character['name']);

    $field = new FieldHidden('operation', 'create');
    $form->addField($field);
  }
  $form->setTitle($title);

  $markup = htmlWrap('span', $character['pb'], array('class' => array('pb')));
  $field = new FieldMarkup('pb', 'Proficiency Bonus', $markup);
  $form->addField($field);

  if ($skill_id)
  {
    $attributes = getAttributeList();
    $skill = getSkill($character_skill['skill_id']);
    $character_attribute = getCharacterAttribute($character_id, $skill['attribute_id']);
    $markup = htmlWrap('span', $character_attribute['modifier'], array('class' => array('attribute_modifier')));
    $field = new FieldMarkup('attribute_modifier', $attributes[$skill['attribute_id']] . ' Modifier', $markup);
    $form->addField($field);
  }

  // Skill List.
  $table = new TableTemplate();
  $table->setHeader(array('Attr', 'Prof', 'Modifier'));
  foreach($character_skills as $character_skill)
  {
    $row = array();
    $attr = array(
      'href' => '/character/skill?character_id=' . $character_id . '&skill_id=' . $character_skill['skill_id'],
    );
    $row[] = htmlWrap('a', $skills[$character_skill['skill_id']], $attr);
    $row[] = $character_skill['proficiency'];
    $row[] = $character_skill['modifier'];
    $table->addRow($row);
  }

  $attr = array(
    'href' => '/character/skill?character_id=' . $character_id,
  );
  $links = htmlWrap('a', 'Add New Skill', $attr) . '<br>';

  $attr = array(
    'href' => '/character?id=' . $character_id,
  );
  $links .= htmlWrap('a', 'Back to ' . $character['name'], $attr);

  $field = new FieldMarkup('skills', 'Skills', $table . $links);
  $form->addField($field);

  // Character.
  $field = new FieldHidden('character_id');
  $field->setValue($character_id);
  $form->addField($field);

  // Skill.
  if (!$skill_id)
  {
    $options = $skills;
    foreach ($character_skills as $character_skill)
    {
      unset($options[$character_skill['skill_id']]);
    }
    $field = new FieldSelect('skill_id', 'Skill', $options);
    $form->addField($field);
  }
  else
  {
    $field = new FieldHidden('skill_id');
    $form->addField($field);
  }

  // Proficiency.
  $field = new FieldNumber('proficiency', 'Proficiency Multiplier');
  $field->setValue(0);
  $form->addField($field);

  // Modifier.
  $field = new FieldNumber('modifier', 'Modifier');
  $field->setValue(-1);
  $form->addField($field);

  // Submit
  $value = 'Add';
  if ($skill_id)
  {
    $value = 'Update';
  }
  $field = new FieldSubmit('submit', $value);
  $form->addField($field);

  if ($skill_id)
  {
    $field = new FieldSubmit('delete', 'Delete');
    $form->addField($field);
  }

  $template->setForm($form);

  return $template;
}

function characterSkillUpsertSubmit()
{
  $character_skill = $_POST;
  unset($character_skill['submit']);
  unset($character_skill['operation']);

  if (isset($_POST['delete']))
  {
    deleteCharacterSkill($character_skill);
    redirect('/character?id=' . $character_skill['character_id']);
  }
  // Update.
  elseif ($_POST['operation'] == 'update')
  {
    updateCharacterSkill($character_skill);
    return htmlWrap('h3', 'Updated.');
  }
  // Create.
  else
  {
    createCharacterSkill($character_skill);
    return htmlWrap('h3', 'Created.');
  }
}

/******************************************************************************
 *
 * Character language Upsert
 *
 ******************************************************************************/
function characterLanguageUpsertForm()
{
  $template = new FormTemplate();
  $template->addCssFilePath('/themes/default/css/character.css');
  $template->addJsFilePath('/modules/character/character.js');

  // Submit.
  if (isset($_SERVER['REQUEST_METHOD']) && ($_SERVER['REQUEST_METHOD'] == 'POST'))
  {
    $template->addMessage(characterLanguageUpsertSubmit());
  }

  $character_id = getUrlID('character_id');
  if (!$character_id)
  {
    $template->addMessage('Missing parameter character_id.');
    return $template;
  }
  $character = getCharacter($character_id);
  $language_id = getUrlID('language_id');
  $languages = getlanguageList();
  $character_languages = getCharacterlanguages($character_id);

  $form = new Form('character_language_form');
  if ($language_id)
  {
    $character_language = getCharacterlanguage($character_id, $language_id);
    $form->setValues($character_language);
    $title = 'Delete character ' . htmlWrap('em', $character['name']) . '\'s language ' . htmlWrap('em', $languages[$character_language['language_id']]);

    $field = new FieldHidden('operation', 'delete');
    $form->addField($field);
  }
  else
  {
    $title = 'Add New language to ' . htmlWrap('em', $character['name']);

    $field = new FieldHidden('operation', 'create');
    $form->addField($field);
  }
  $form->setTitle($title);

  // Language list.
  $list = array();
  foreach($character_languages as $character_language)
  {
    $attr = array(
      'href' => '/character/language?character_id=' . $character_id . '&language_id=' . $character_language['language_id'],
    );
    $list[] = htmlWrap('a', $languages[$character_language['language_id']], $attr);
  }

  $attr = array(
    'href' => '/character/language?character_id=' . $character_id,
  );
  $links = htmlWrap('a', 'Add New language', $attr) . '<br>';

  $attr = array(
    'href' => '/character?id=' . $character_id,
  );
  $links .= htmlWrap('a', 'Back to ' . $character['name'], $attr);

  $field = new FieldMarkup('languages', '<none>', 'Languages: ' . implode(', ', $list) . '<br>' . $links);
  $form->addField($field);

  // Character.
  $field = new FieldHidden('character_id');
  $field->setValue($character_id);
  $form->addField($field);

  // Submit
  if ($language_id)
  {
    $field = new FieldHidden('language_id');
    $form->addField($field);

    $field = new FieldSubmit('delete', 'Delete');
    $form->addField($field);
  }
  else
  {
    $options = $languages;
    foreach ($character_languages as $character_language)
    {
      unset($options[$character_language['language_id']]);
    }
    $field = new FieldSelect('language_id', 'language', $options);
    $form->addField($field);

    $field = new FieldSubmit('submit', 'Add');
    $form->addField($field);
  }

  $template->setForm($form);

  return $template;
}

function characterLanguageUpsertSubmit()
{
  $character_language = $_POST;
  unset($character_language['submit']);
  unset($character_language['operation']);

  if (isset($_POST['delete']))
  {
    deleteCharacterlanguage($character_language);
    redirect('/character?id=' . $character_language['character_id']);
  }
  // Create.
  else
  {
    createCharacterlanguage($character_language);
    return htmlWrap('h3', 'Created.');
  }
}
