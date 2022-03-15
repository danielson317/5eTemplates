<?php
/******************************************************************************
 *
 * Character Skill Upsert
 *
 ******************************************************************************/

function characterSkillUpsertFormAjax()
{
  $response = getAjaxDefaultResponse();

  // Submit.
  $operation = getUrlOperation();
  if ($operation === 'list')
  {
    characterSkillListAjax();
  }
  elseif (isset($_SERVER['REQUEST_METHOD']) && ($_SERVER['REQUEST_METHOD'] == 'POST'))
  {
    characterSkillUpsertSubmitAjax();
  }

  $character_id = getUrlID('character_id');
  if (!$character_id)
  {
    die('Missing parameter character_id.');
  }
  $character = getCharacter($character_id);
  $skill_id = getUrlID('skill_id');
  $skills = getSkillList();
  $character_skill_map = getCharacterSkillList($character_id);

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
    $abilities = getAbilityList();
    $skill = getSkill($character_skill['skill_id']);
    $character_ability = getCharacterability($character_id, $skill['ability_id']);
    $markup = htmlWrap('span', $character_ability['modifier'], array('class' => array('ability_modifier')));
    $field = new FieldMarkup('ability_modifier', $abilities[$skill['ability_id']] . ' Modifier', $markup);
    $form->addField($field);
  }

  // Character id.
  $field = new FieldHidden('character_id');
  $field->setValue($character_id);
  $form->addField($field);

  // Skill.
  if (!$skill_id)
  {
    $options = $skills;
    foreach ($character_skill_map as $character_skill)
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

  $response['data'] = $form->__toString();
  jsonResponseDie($response);
}

function characterSkillListAjax()
{
  $response = getAjaxDefaultResponse();
  $character_id = getUrlID('character_id');

  $output = '';
  // Skill List.
  $table = new TableTemplate();
  $skills = getSkillList();
  $character_skill_map = getCharacterSkillList($character_id);
  foreach($character_skill_map as $character_skill)
  {
    $row = array();
    $attr = array(
      'query' => array(
        'character_id' => $character_id,
        'skill_id' => $character_skill['skill_id'],
      ),
      'class' => array('skill'),
    );
    $row[] = a($skills[$character_skill['skill_id']], '/character/skill', $attr);
    $row[] = $character_skill['proficiency'];
    $row[] = $character_skill['modifier'];
    $output .= $table::tableRow($row);
  }

  $response['data'] = $output;

  jsonResponseDie($response);
}

function characterSkillUpsertSubmitAjax()
{
  $response = getAjaxDefaultResponse();
  $character_skill = $_POST;

  if (isset($_POST['delete']))
  {
    deleteCharacterSkill($character_skill);
    $response['data'] = 'Deleted';
  }
  // Update.
  elseif ($_POST['operation'] == 'update')
  {
    updateCharacterSkill($character_skill);
    $response['data'] = 'Updated';
  }
  // Create.
  else
  {
    createCharacterSkill($character_skill);
    $response['data'] = 'Created';
  }

  jsonResponseDie($response);
}
