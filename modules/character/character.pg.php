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

  $template = new ListPageTemplate('Characters');
  $template->addCssFilePath('/themes/default/css/character.css');

  // Operations.
  $template->addOperation(a('New Character', '/character'));

  if ($page > 1)
  {
    $attr = array(
      'query' => array('page' => $page - 1),
    );
    $template->addOperation(a('Prev Page', '/character', $attr));
  }

  if (count($characters) >= PAGER_SIZE_DEFAULT)
  {
    $attr = array(
      'query' => array('page' => $page + 1),
    );
    $template->addOperation(a('Next Page', '/character', $attr));
  }

  // List
  $table = new TableTemplate();
  $table->setAttr('class', array('character-list'));
  $table->setHeader(array('Name', 'Class', 'Subclass', 'Level', 'Race', 'Background', 'Player', 'Ops'));

  $classes = getClassList();
  $subclasses = getSubclassList();
  $races = getRaceList();
  $players = getPlayerList();
  $backgrounds = getBackgroundList();
  foreach ($characters as $character)
  {
    $row = array();
    $attr = array(
      'query' => array(
        'id' => $character['id']
      ),
    );
    $row[] = a($character['name'], '/character', $attr);

    $character_class_map = getCharacterClassList($character['id']);
    $class = [];
    $subclass = [];
    $level = [];
    foreach ($character_class_map as $character_class)
    {
      $class[] = $classes[$character_class['class_id']];
      $subclass[] = $character_class['subclass_id'] ? $subclasses[$character_class['subclass_id']] : '';
      $level[] = $character_class['level'];
    }
    $row[] = join('/', $class);
    $row[] = join('/', $subclass);
    $row[] = join('/', $level);
    $row[] = iis($races, $character['race_id']);
    $row[] = iis($backgrounds, $character['background_id']);
    $row[] = iis($players, $character['player_id']);

    $attr = array(
      'query' => array(
        'id' => $character['id']
      ),
    );
    $row[] = a('Print', '/character/print', $attr);
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
  $template = new FormPageTemplate();
  $template->addCssFilePath('/themes/default/css/character.css');
  $template->addJsFilePath('/modules/character/character.js');

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
    $title = sanitize($character['name']);
  }
  $form->setTitle($title);

  // ID.
  $field = new FieldHidden('id');
  $form->addField($field);

  /*****************
   * Header Group
   *****************/
  $group = 'header_name';
  $form->addGroup($group);

  // Name.
  $field = new FieldText('name', 'Character Name');
  $field->setGroup($group)->setRequired();
  $form->addField($field);

  // Submit
  $value = 'Create';
  if ($character_id)
  {
    $value = 'Update';
  }
  $field = new FieldSubmit('submit', $value);
  $field->setGroup($group);
  $form->addField($field);

  /*****************
   * Header Group
   *****************/
  $group = 'header_upper';
  $form->addGroup($group);

  // Classes
  $total_level = 0;
  $classes = getClassList();
  $subclasses = getSubclassList();
  if ($character_id)
  {
    $table = new TableTemplate('character-class');
    $table->setHeader(array('Class', 'Subclass', 'Level'));
    $character_class_map = getCharacterClassList($character_id);
    foreach ($character_class_map as $character_class)
    {

      $row = array();
      $attr = array(
        'query' => array(
          'character_id' => $character_id, 
          'class_id' => $character_class['class_id'],
        ),
        'class' => 'class'
      );
      $row[] = a($classes[$character_class['class_id']], '/character/class', $attr);
      $row[] = ($character_class['subclass_id'] > 0) ? $subclasses[$character_class['subclass_id']] : '';
      $row[] = $character_class['level'];
      $table->addRow($row);
      $total_level += $character_class['level'];
    }

    $attr = array(
      'query' => array('character_id' => $character_id),
      'class' => array('add-class'),
    );
    $link = a('Add New Class', '/character/class', $attr);

    $field = new FieldMarkup('classes', 'Classes', $table . $link);
    $field->setGroup($group);
    $form->addField($field);
  }
  else
  {
    $field = new FieldSelect('class_id', 'Class', $classes);
    $field->setGroup($group);
    $form->addField($field);
  }

  // Background.
  $backgrounds = getBackgroundList();
  $field = new FieldSelect('background_id', 'Background', $backgrounds);
  $field->setGroup($group);
  $form->addField($field);

  // Player.
  $options = array(0 => '--Select One--') + getPlayerList();
  $field = new FieldSelect('player_id', 'Player', $options);
  $field->setGroup($group);
  $field->setRequired();
  $form->addField($field);

  /*****************
   * Header Group
   *****************/
  $group = 'header_lower';
  $form->addGroup($group);

  // Race.
  $options = array(0 => '--Select One--') + getRaceCompleteList();
  $field = new FieldSelect('subrace_id', 'Race', $options);
  $field->setGroup($group);
  $field->setRequired();
  $form->addField($field);

  // Alignment.
  $options = array(0 => '--Select One--') + getAlignmentList();
  $field = new FieldSelect('alignment', 'Alignment', $options);
  $field->setGroup($group);
  $form->addField($field);

  // XP.
  $field = new FieldNumber('xp', 'Experience Points');
  $field->setGroup($group);
  $form->addField($field);

  /********************
   * Abilities Group
   ********************/
  $group = 'abilities_group';
  $form->addGroup($group);

  $abilities = getAbilityList();
  $character_ability_map = getCharacterabilityList($character_id);
  $table = new TableTemplate();
  $table->setHeader(array('Ability', 'Score', 'Mod', 'Prof', 'ST'));
  foreach($character_ability_map as $character_ability)
  {
    $row = array();
    $attr = array(
      'query' => array(
        'character_id' => $character_id,
        'ability_id' => $character_ability['ability_id'],
      ),
      'class' => array('ability'),
    );
    $row[] = a($abilities[$character_ability['ability_id']], '/ajax/character/ability', $attr);
    $row[] = $character_ability['score'];
    $row[] = getAbilityModifier($character_ability['score']);
    $row[] = $character_ability['proficiency_multiplier'];
    $row[] = getSkillModifier($character_ability['score'], $total_level, $character_ability['proficiency_multiplier']);
    $table->addRow($row);
  }

  $attr = array(
    'query' => array(
      'character_id' => $character_id,
    ),
    'class' => array('add-ability'),
  );
  $link = a('Add New ability', '/ajax/character/ability', $attr);

  $field = new FieldMarkup('ability', 'Abilities', $table . $link);
  $field->setGroup($group);
  $form->addField($field);

  // Skills
  $skills = getSkillList();
  $character_skill_map = getCharacterSkillList($character_id);
  $table = new TableTemplate();
  $table->setHeader(array('Skill', 'Prof', 'Mod'));
  foreach($character_skill_map as $character_skill)
  {
    $skill = getSkill($character_skill['skill_id']);
    $character_ability = getCharacterAbility($character_id, $skill['ability_id']);
    $row = array();
    $attr = array(
      'query' => array(
        'character_id' => $character_id,
        'skill_id' => $character_skill['skill_id']
      ),
      'class' => array('skill'),
    );
    $row[] = a($skills[$character_skill['skill_id']], '/ajax/character/skill', $attr);
    $row[] = $character_skill['proficiency_multiplier'];
    $row[] = getSkillModifier($character_ability['score'], $total_level, $character_skill['proficiency_multiplier']);
    $table->addRow($row);
  }

  $attr = array(
    'query' => array(
      'character_id' => $character_id
    ),
    'class' => array('add-skill'),
  );
  $link = a('Add New Skill', '/ajax/character/skill', $attr);

  $field = new FieldMarkup('skills', 'Skills', $table . $link);
  $field->setGroup($group);
  $form->addField($field);

  /*******************
   * Proficiencies.
   *******************/

//  $proficiency_table = getCharacterProficiencyTable($character_id);
//
//  // Field wrapper.
//  $field = new FieldMarkup('proficiencies', 'Proficiencies', $proficiency_table->__toString());
//  $field->setGroup($group);
//  $form->addField($field);
//
//  /*****************
//   * Stats Group
//   *****************/
//  $group = 'stats';
//  $form->addGroup($group);
//
//  // AC.
//  $field = new FieldNumber('ac', 'Armor Class');
//  $field->setGroup($group);
//  $form->addField($field);
//
//  // Max HP.
//  $field = new FieldNumber('hp', 'Max HP');
//  $field->setGroup($group);
//  $form->addField($field);
//
//  // Proficiency Bonus.
//  $field = new FieldNumber('pb', 'Proficiency Bonus');
//  $field->setValue(2);
//  $field->setGroup($group);
//  $form->addField($field);
//
//  // Speed.
//  $field = new FieldNumber('speed', 'Speed');
//  $field->setValue(30);
//  $field->setGroup($group);
//  $form->addField($field);
//
//  // Hit Dice
//  $dice = getDieList();
//  $character_dice = getCharacterDieList($character_id);
//  $list = array();
//  foreach($character_dice as $character_die)
//  {
//    $attr = array(
//      'query' => array(
//        'character_id' => $character_id,
//        'die_id' => $character_die['skill_id']
//      ),
//      'class' => array('skill'),
//    );
//    $list[] = a($skills[$dice['die_id']], '/ajax/character/hit-die', $attr);
//  }
//
//  $attr = array(
//    'query' => array(
//      'character_id' => $character_id
//    ),
//    'class' => array('add-skill'),
//  );
//  $link = a('Add Hit Die', '/ajax/character/hit-die', $attr);
//
//  $field = new FieldMarkup('hit_dice', 'Hit Dice', implode(',', $list));
//  $field->setGroup($group);
//  $form->addField($field);

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
function characterClassUpsertFormAjax()
{
  $response = getAjaxDefaultResponse();

  // Submit.
  $operation = getUrlOperation();
  if ($operation === 'list')
  {
    characterClassListAjax();
  }
  elseif (isset($_SERVER['REQUEST_METHOD']) && ($_SERVER['REQUEST_METHOD'] == 'POST'))
  {
    characterClassUpsertSubmitAjax();
  }

  $character_id = getUrlID('character_id');
  if (!$character_id)
  {
    return htmlWrap('h3', 'Missing parameter character_id.');
  }
  $character = getCharacter($character_id);
  $class_id = getUrlID('class_id');
  $classes = getClassList();
  $character_class_map = getCharacterClassList($character_id);

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

  // Character ID.
  $field = new FieldHidden('character_id');
  $field->setValue($character_id);
  $form->addField($field);

  // Class.
  if (!$class_id)
  {
    $options = array(0 => '--Select One--') + $classes;
    foreach ($character_class_map as $character_class)
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

  $response['data'] = $form->__toString();
  jsonResponseDie($response);
}

function characterClassUpsertSubmitAjax()
{
  $response = getAjaxDefaultResponse();

  $character_class = $_POST;

  // Delete.
  if (isset($_POST['delete']))
  {
    deleteCharacterClass($character_class);
    $response['data'] = 'Deleted';
  }
  // Update.
  elseif ($_POST['operation'] == 'update')
  {
    updateCharacterClass($character_class);
    $response['data'] = 'Updated.';
  }
  // Create.
  else
  {
    createCharacterClass($character_class);
    $response['data'] = 'Created.';
  }
  jsonResponseDie($response);
}

function characterClassListAjax()
{
  $response = getAjaxDefaultResponse();
  $character_id = getUrlID('character_id');

  $output = '';
  $classes = getClassList();
  $subclasses = getSubclassList();
  $character_class_map = getCharacterClassList($character_id);
  foreach ($character_class_map as $character_class)
  {

    $row = array();
    $attr = array(
      'query' => array(
        'character_id' => $character_id,
        'class_id' => $character_class['class_id'],
      ),
      'class' => 'class'
    );
    $row[] = a($classes[$character_class['class_id']], '/character/class', $attr);
    $row[] = ($character_class['subclass_id'] > 0) ? $subclasses[$character_class['subclass_id']] : '';
    $row[] = $character_class['level'];
    $output .= TableTemplate::tableRow($row);
  }
  $response['data'] = $output;

  jsonResponseDie($response);
}



/******************************************************************************
 *
 * Proficiency Table Refresh
 *
 ******************************************************************************/
function characterItemTypeProficiencyAjax()
{
  $response = getAjaxDefaultResponse();

  try
  {
    $operation = getUrlOperation();
    if ($operation !== 'list')
    {
      throw new AjaxException('Unknown operation', EXCEPTION_UNKNOWN_OPTION);
    }

    $character_id = getUrlID('character_id');

    $proficiency_table = getCharacterProficiencyTable($character_id);

    $response['data'] = $proficiency_table->__toString();
  }
  catch(AjaxException $e)
  {
    $response['status'] = FALSE;
    $response['data'] = $e->getMessage();
  }

  jsonResponseDie($response);
}

/******************************************************************************
 *
 * Character Language Upsert
 *
 ******************************************************************************/
function characterLanguageUpsertFormAjax()
{
  $response = getAjaxDefaultResponse();

  $operation = getUrlOperation();
  if ($operation === 'list')
  {
    characterLanguageListAjax();
  }
  if (isset($_SERVER['REQUEST_METHOD']) && ($_SERVER['REQUEST_METHOD'] == 'POST'))
  {
    characterLanguageUpsertSubmitAjax();
  }

  $character_id = getUrlID('character_id');
  if (!$character_id)
  {
    $response['status'] = FALSE;
    $response['data'] = 'Missing parameter character_id.';
    jsonResponseDie($response);
  }
  $character = getCharacter($character_id);
  $language_id = getUrlID('language_id');
  $languages = getlanguageList();
  $character_language_maps = getCharacterLanguageList($character_id);

  $form = new Form('character_language_map_form');
  if ($language_id)
  {
    $character_language_map = getCharacterlanguage($character_id, $language_id);
    $form->setValues($character_language_map);
    $title = 'Delete character ' . htmlWrap('em', $character['name']) . '\'s language ' . htmlWrap('em', $languages[$character_language_map['language_id']]);

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
    foreach ($character_language_maps as $character_language_map)
    {
      unset($options[$character_language_map['language_id']]);
    }
    $field = new FieldSelect('language_id', 'language', $options);
    $form->addField($field);

    $field = new FieldSubmit('submit', 'Add');
    $form->addField($field);
  }

  $response['data'] = $form->__toString();

  jsonResponseDie($response);
}

function characterLanguageListAjax()
{
  $response = getAjaxDefaultResponse();
  $character_id = getUrlID('character_id');


  $languages = getLanguageList();
  $character_language_maps = getCharacterLanguageList($character_id);

  $list = array();
  foreach($character_language_maps as $character_language_map)
  {
    $attr = array(
      'query' => array(
        'character_id' => $character_id,
        'language_id' => $character_language_map['language_id'],
      ),
      'class' => array('language'),
    );
    $list[] = a($languages[$character_language_map['language_id']], '/ajax/character/language', $attr);
  }

  $response['data'] = implode(', ', $list);
  jsonResponseDie($response);
}

function characterLanguageUpsertSubmitAjax()
{
  $response = getAjaxDefaultResponse();
  $character_language_map = $_POST;

  if (isset($_POST['delete']))
  {
    deleteCharacterlanguage($character_language_map);
  }
  // Create.
  else
  {
    createCharacterlanguage($character_language_map);
  }

  jsonResponseDie($response);
}

/******************************************************************************
 *
 * Character Item Proficiency Upsert
 *
 ******************************************************************************/
function characterItemProficiencyUpsertFormAjax()
{
  $response = getAjaxDefaultResponse();

  if (isset($_SERVER['REQUEST_METHOD']) && ($_SERVER['REQUEST_METHOD'] == 'POST'))
  {
    characterItemProficiencyUpsertSubmitAjax();
  }

  $character_id = getUrlID('character_id');
  $item_id = getUrlID('item_id');
  if (!$character_id)
  {
    $response['status'] = FALSE;
    $response['data'] = 'Missing parameter character_id.';
    jsonResponseDie($response);
  }

  $character = getCharacter($character_id);

  $form = new Form('character_item_proficiency_map_form');
  if ($item_id)
  {
    $character_item_proficiency = getCharacterItemProficiency($character_id, $item_id);
    $form->setValues($character_item_proficiency);
    $title = 'Delete character ' . htmlWrap('em', $character['name']) . '\'s item proficiency.';

    $field = new FieldHidden('operation', 'delete');
    $form->addField($field);
  }
  else
  {
    $title = 'Add New item proficiency to ' . htmlWrap('em', $character['name']);

    $field = new FieldHidden('operation', 'create');
    $form->addField($field);
  }
  $form->setTitle($title);

  // Character.
  $field = new FieldHidden('character_id');
  $field->setValue($character_id);
  $form->addField($field);

  // Submit
  if ($item_id)
  {
    $field = new FieldHidden('item_id');
    $form->addField($field);

    $field = new FieldSubmit('delete', 'Delete');
    $form->addField($field);
  }
  else
  {
    $field = new FieldAutocomplete('item_id', 'Item', '/ajax/item/autocomplete');
    $form->addField($field);
  }

  $field = new FieldNumber('proficiency', 'Proficiency Multiplier');
  $field->setValue(0);
  $form->addField($field);

  $field = new FieldSubmit('submit', 'Add');
  $form->addField($field);

  $response['data'] = $form->__toString();

  jsonResponseDie($response);
}

function characterItemProficiencyUpsertSubmitAjax()
{
  $response = getAjaxDefaultResponse();
  $character_item_proficiency_map = $_POST;

  if (isset($_POST['delete']))
  {
    deleteCharacteritemProficiency($character_item_proficiency_map);
  }
  elseif ($character_item_proficiency_map['item_id'])
  {
    updateCharacterItemProficiency($character_item_proficiency_map);
  }
  else
  {
    createCharacterItemProficiency($character_item_proficiency_map);
  }

  jsonResponseDie($response);
}

/******************************************************************************
 *
 * Character Item Proficiency Upsert
 *
 ******************************************************************************/
function characterItemTypeProficiencyUpsertFormAjax()
{
  $response = getAjaxDefaultResponse();

  if (isset($_SERVER['REQUEST_METHOD']) && ($_SERVER['REQUEST_METHOD'] === 'POST'))
  {
    characterItemTypeProficiencyUpsertSubmitAjax();
  }

  $character_id = getUrlID('character_id');
  $item_type_id = getUrlID('item_type_id');
  if (!$character_id)
  {
    $response['status'] = FALSE;
    $response['data'] = 'Missing parameter character_id.';
    jsonResponseDie($response);
  }

  $character = getCharacter($character_id);

  $form = new Form('character_item_type_proficiency_map_form');
  if ($item_type_id)
  {
    $character_item_type_proficiency = getCharacterItemTypeProficiency($character_id, $item_type_id);
    $form->setValues($character_item_type_proficiency);
    $title = 'Delete character ' . htmlWrap('em', $character['name']) . '\'s item type proficiency.';

    $field = new FieldHidden('operation', 'delete');
    $form->addField($field);
  }
  else
  {
    $title = 'Add new item type proficiency to ' . htmlWrap('em', $character['name']);

    $field = new FieldHidden('operation', 'create');
    $form->addField($field);
  }
  $form->setTitle($title);

  // Character.
  $field = new FieldHidden('character_id');
  $field->setValue($character_id);
  $form->addField($field);

  // Submit
  if ($item_type_id)
  {
    $field = new FieldHidden('item_type_id');
    $form->addField($field);

    $field = new FieldSubmit('delete', 'Delete');
    $form->addField($field);
  }
  else
  {
    $options = getItemTypeList();
    $field = new FieldSelect('item_type_id', 'Item', $options);
    $form->addField($field);

    $field = new FieldSubmit('submit', 'Add');
    $form->addField($field);
  }

  $response['data'] = $form->__toString();

  jsonResponseDie($response);
}

function characterItemTypeProficiencyUpsertSubmitAjax()
{
  $response = getAjaxDefaultResponse();
  $character_item_type_proficiency_map = $_POST;

  if (isset($_POST['delete']))
  {
    deleteCharacterItemTypeProficiency($character_item_type_proficiency_map);
  }
  // Create.
  else
  {
    createCharacterItemTypeProficiency($character_item_type_proficiency_map);
  }

  jsonResponseDie($response);
}
