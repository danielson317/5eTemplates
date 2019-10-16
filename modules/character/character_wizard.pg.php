<?php

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
  
  $form_item = new FieldText('name', 'Name');
  $form->addField($form_item);

  $players = getPlayerList();
  $form_item = new FieldSelect('player_id', 'Player', $players);
  $form->addField($form_item);

  $races = getRaceList();
  $form_item = new FieldSelect('race_id', 'Race', $races);
  $form->addField($form_item);
  
  $subraces = getSubraceList(key($races));
  $form_item = new FieldSelect('subrace_id', 'Subrace', $subraces);
  $form->addField($form_item);

  $alignments = getAlignmentList();
  $form_item = new FieldSelect('alignment', 'Alignment', $alignments);
  $form->addField($form_item);

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

  $form_item = new FieldHidden('character_id', $character_id);
  $form->addField($form_item);

  $classes = getClassList();
  $form_item = new FieldSelect('class_id', 'Class', $classes);
  $form->addField($form_item);

  $subclass = array('' => '--select one--') + getSubclassList(key($classes));
  $form_item = new FieldSelect('subclass_id', 'Subclass', $subclass);
  $form->addField($form_item);

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

  $form_item = new FieldHidden('character_id', $character_id);
  $form->addField($form_item);

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
  unset($_POST['Continue']);

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

  redirect('/character/wizard/background', '303', array('query' => array('character_id' => $character_id)));
}
