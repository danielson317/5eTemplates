<?php

/****************************************************************************
 *
 *  Race
 *
 ****************************************************************************/
function characterWizardRaceForm()
{
  $template = new FormPageTemplate();
  $template->addCssFilePath('/themes/default/css/character.css');
  $template->addJsFilePath('/modules/character/character.js');

  // Submit.
  if (isset($_SERVER['REQUEST_METHOD']) && ($_SERVER['REQUEST_METHOD'] === 'POST'))
  {
    characterWizardRaceSubmit();
  }

  $form = new Form('character_wizard_race_form');
  $form->setTitle('Choose your race.');

  $players = getPlayerList();
  $field = new FieldSelect('player_id', 'Player', $players);
  $form->addField($field);

  $field = new FieldText('name', 'Name');
  $form->addField($field);

  $gender = getGenderList();
  $field = new FieldSelect('gender', 'Gender', $gender);
  $form->addField($field);

  $races = getRaceList();
  $field = new FieldSelect('race_id', 'Race', $races);
  $form->addField($field);
  
  $subraces = getSubraceList(key($races));
  $field = new FieldSelect('subrace_id', 'Subrace', $subraces);
  $form->addField($field);

  $alignments = getAlignmentList();
  $field = new FieldSelect('alignment', 'Alignment', $alignments);
  $form->addField($field);

  $backgrounds = getBackgroundList();
  $field = new FieldSelect('background_id', 'Background', $backgrounds);
  $form->addField($field);

  $field = new FieldSubmit('submit', 'Create');
  $form->addField($field);

  $template->setForm($form);
  return $template;
}

function characterWizardRaceSubmit()
{
  $character = $_POST;
  $character['id'] = createCharacterSimple($character);
  redirect('/character/wizard/class', '303', array('query' => array('character_id' => $character['id'])));
}

/****************************************************************************
 *
 * Class
 *
 ****************************************************************************/
function characterWizardClassForm()
{
  $character_id = getUrlID('character_id');
  if (!$character_id)
  {
    redirect('/characters');
  }

  $template = new FormPageTemplate();
  $template->addCssFilePath('/themes/default/css/character.css');
  $template->addJsFilePath('/modules/character/character.js');
  $template->setUpper(characterDisplay($character_id));

  // Submit.
  if (isset($_SERVER['REQUEST_METHOD']) && ($_SERVER['REQUEST_METHOD'] === 'POST'))
  {
    characterWizardClassSubmit();
  }

  $form = new Form('character_wizard_class_form');
  $form->setTitle('Choose your class.');

  $field = new FieldHidden('character_id', $character_id);
  $form->addField($field);

  $classes = getClassList();
  $field = new FieldSelect('class_id', 'Class', $classes);
  $form->addField($field);

  $subclass = array('' => '--select one--') + getSubclassList(key($classes));
  $field = new FieldSelect('subclass_id', 'Subclass', $subclass);
  $form->addField($field);

  $field = new FieldSubmit('submit', 'Continue');
  $form->addField($field);

  $template->setForm($form);
  return $template;
}

function characterWizardClassSubmit()
{
  $character_class = $_POST;
  $character_class['level'] = 1;
  createCharacterClass($character_class);
  redirect('/character/wizard/ability', '303', array('query' => array('character_id' => $character_class['character_id'])));
}

/****************************************************************************
 *
 * Ability
 *
 ****************************************************************************/
function characterWizardAbilityForm()
{
  $character_id = getUrlID('character_id');
  if (!$character_id)
  {
    redirect('/characters');
  }

  $template = new FormPageTemplate();
  $template->addCssFilePath('/themes/default/css/character.css');
  $template->addJsFilePath('/modules/character/character.js');
  $template->setUpper(characterDisplay($character_id));

  // Submit.
  if (isset($_SERVER['REQUEST_METHOD']) && ($_SERVER['REQUEST_METHOD'] === 'POST'))
  {
    characterWizardAbilitySubmit();
  }

  $form = new Form('character_wizard_ability_form');
  $form->setTitle('Choose your ability scores.');

  $field = new FieldHidden('character_id', $character_id);
  $form->addField($field);

  $abilities = getAbilityList();
  foreach($abilities as $id => $ability)
  {
    $field = new FieldNumber($id, $ability);
    $field->setValue(8);
    $form->addField($field);
  }

  $field = new FieldSubmit('submit', 'Continue');
  $form->addField($field);

  $template->setForm($form);
  return $template;
}

function characterWizardAbilitySubmit()
{
  $character_id = $_POST['character_id'];
  unset($_POST['character_id']);
  unset($_POST['submit']);

  $character_abilities = $_POST;
  foreach ($character_abilities as $ability_id => $ability_score)
  {
    $character_ability = array(
      'character_id' => $character_id,
      'ability_id' => $ability_id,
      'score' => $ability_score,
      'modifier' => floor($ability_score/2) - 5,
      'proficiency' => 0,
      'saving_throw' => floor($ability_score/2) - 5,
    );
    createCharacterAbility($character_ability);
  }

  redirect('/character/wizard/skill', '303', array('query' => array('character_id' => $character_id)));
}

/****************************************************************************
 *
 * Skills
 *
 ****************************************************************************/
function characterWizardSkillForm()
{
  $character_id = getUrlID('character_id');
  if (!$character_id)
  {
    redirect('/characters');
  }

  $template = new FormPageTemplate();
  $template->addCssFilePath('/themes/default/css/character.css');
  $template->addJsFilePath('/modules/character/character.js');
  $template->setUpper(characterDisplay($character_id));

  // Submit.
  if (isset($_SERVER['REQUEST_METHOD']) && ($_SERVER['REQUEST_METHOD'] === 'POST'))
  {
    characterWizardSkillSubmit();
  }

  $form = new Form('character_wizard_skills_form');
  $form->setTitle('Choose your skills.');

  $field = new FieldHidden('character_id', $character_id);
  $form->addField($field);

  $skills = getSkillList();
  foreach($skills as $id => $skill)
  {
    $field = new FieldNumber($id, $skill);
    $field->setValue(0);
    $form->addField($field);
  }

  $field = new FieldSubmit('submit', 'Continue');
  $form->addField($field);

  $template->setForm($form);
  return $template;
}

function characterWizardSkillSubmit()
{
  $character_id = $_POST['character_id'];
  unset($_POST['character_id']);
  unset($_POST['submit']);

  foreach($_POST as $skill_id => $modifier)
  {
    $character_skill = array(
      'character_id' => $character_id,
      'skill_id' => $skill_id,
      'proficiency' => 0,
      'modifier' => $modifier,
    );
    createCharacterSkill($character_skill);
  }

  redirect('/character/wizard/proficiency', '303', array('query' => array('character_id' => $character_id)));
}

/****************************************************************************
 *
 * Proficiencies
 *
 ****************************************************************************/
function characterWizardProficiencyForm()
{
  $character_id = getUrlID('character_id');
  if (!$character_id)
  {
    redirect('/characters');
  }

  $template = new FormPageTemplate();
  $template->addCssFilePath('/themes/default/css/character.css');
  $template->addJsFilePath('/modules/character/character.js');
  $template->setUpper(characterDisplay($character_id));

  // Submit.
  if (isset($_SERVER['REQUEST_METHOD']) && ($_SERVER['REQUEST_METHOD'] === 'POST'))
  {
    characterWizardProficiencySubmit();
  }

  $form = new Form('character_wizard_proficiency_form');
  $form->setTitle('Choose your skills.');

  $field = new FieldHidden('character_id', $character_id);
  $form->addField($field);

  // Language.
  $field = new FieldMarkup('language_list', 'Languages');
  $form->addField($field);

  $options = array('<none>' => '--Select One--') + getLanguageList();
  $field = new FieldSelect('language_id', 'Add Language', $options);
  $form->addField($field);

  $field = new FieldMarkup('item_list', 'Items');

  $field = new FieldSubmit('submit', 'Continue');
  $form->addField($field);

  $template->setForm($form);
  return $template;
}

function characterWizardProficiencySubmit()
{
  debugPrint($_POST, 'post');
  $character_id = $_POST['character_id'];

  redirect('/character/wizard/personality', '303', array('query' => array('character_id' => $character_id)));
}

/****************************************************************************
 *
 * Personality
 *
 ****************************************************************************/
function characterWizardPersonalityForm()
{
  $character_id = getUrlID('character_id');
  if (!$character_id)
  {
    redirect('/characters');
  }

  $template = new FormPageTemplate();
  $template->addCssFilePath('/themes/default/css/character.css');
  $template->addJsFilePath('/modules/character/character.js');
  $template->setUpper(characterDisplay($character_id));

  // Submit.
  if (isset($_SERVER['REQUEST_METHOD']) && ($_SERVER['REQUEST_METHOD'] === 'POST'))
  {
    characterWizardPersonalitySubmit();
  }

  $form = new Form('character_wizard_personality_form');
  $form->setTitle('Choose your skills.');

  $field = new FieldHidden('character_id', $character_id);
  $form->addField($field);

  $abilities = getAbilityList();
  foreach($abilities as $id => $ability)
  {
    $field = new FieldNumber($id, $ability);
    $field->setValue(8);
    $form->addField($field);
  }

  $field = new FieldSubmit('submit', 'Continue');
  $form->addField($field);

  $template->setForm($form);
  return $template;
}

function characterWizardPersonalitySubmit()
{
  $character_id = $_POST['character_id'];

  redirect('/character/wizard/equipment', '303', array('query' => array('character_id' => $character_id)));
}

/****************************************************************************
 *
 * Equipment
 *
 ****************************************************************************/
function characterWizardEquipmentForm()
{
  $character_id = getUrlID('character_id');
  if (!$character_id)
  {
    redirect('/characters');
  }

  $template = new FormPageTemplate();
  $template->addCssFilePath('/themes/default/css/character.css');
  $template->addJsFilePath('/modules/character/character.js');
  $template->setUpper(characterDisplay($character_id));

  // Submit.
  if (isset($_SERVER['REQUEST_METHOD']) && ($_SERVER['REQUEST_METHOD'] === 'POST'))
  {
    characterWizardEquipmentSubmit();
  }

  $form = new Form('character_wizard_equipment_form');
  $form->setTitle('Choose your equipment.');

  $field = new FieldHidden('character_id', $character_id);
  $form->addField($field);

  $abilities = getAbilityList();
  foreach($abilities as $id => $ability)
  {
    $field = new FieldNumber($id, $ability);
    $field->setValue(8);
    $form->addField($field);
  }

  $field = new FieldSubmit('submit', 'Continue');
  $form->addField($field);

  $template->setForm($form);
  return $template;
}

function characterWizardEquipmentSubmit()
{
  $character_id = $_POST['character_id'];

  redirect('/character/wizard/spells', '303', array('query' => array('character_id' => $character_id)));
}

/****************************************************************************
 *
 * Spells
 *
 ****************************************************************************/
function characterWizardSpellForm()
{
  $character_id = getUrlID('character_id');
  if (!$character_id)
  {
    redirect('/characters');
  }

  $template = new FormPageTemplate();
  $template->addCssFilePath('/themes/default/css/character.css');
  $template->addJsFilePath('/modules/character/character.js');
  $template->setUpper(characterDisplay($character_id));

  // Submit.
  if (isset($_SERVER['REQUEST_METHOD']) && ($_SERVER['REQUEST_METHOD'] === 'POST'))
  {
    characterWizardSpellSubmit();
  }

  $form = new Form('character_wizard_spell_form');
  $form->setTitle('Choose your equipment.');

  $field = new FieldHidden('character_id', $character_id);
  $form->addField($field);

  $abilities = getAbilityList();
  foreach($abilities as $id => $ability)
  {
    $field = new FieldNumber($id, $ability);
    $field->setValue(8);
    $form->addField($field);
  }

  $field = new FieldSubmit('submit', 'Continue');
  $form->addField($field);

  $template->setForm($form);
  return $template;
}

function characterWizardSpellSubmit()
{
  $character_id = $_POST['character_id'];

  redirect('/character', '303', array('query' => array('character_id' => $character_id)));
}
