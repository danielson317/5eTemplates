<?php

/******************************************************************************
 *
 * Character ability Upsert
 *
 ******************************************************************************/
function characterAbilityUpsertFormAjax()
{
  $response = getAjaxDefaultResponse();

  // Submit.
  $operation = getUrlOperation();
  if ($operation === 'list')
  {
    characterAbilityListAjax();
  }
  elseif (isset($_SERVER['REQUEST_METHOD']) && ($_SERVER['REQUEST_METHOD'] == 'POST'))
  {
    characterAbilityUpsertSubmitAjax();
  }

  // Arguments.
  $character_id = getUrlID('character_id');
  if (!$character_id)
  {
    die('Missing parameter character_id.');
  }
  $character = getCharacter($character_id);
  $ability_id = getUrlID('ability_id');
  $abilities = getAbilityList();
  $character_ability_map = getCharacterAbilityList($character_id);

  // Form.
  $form = new Form('character_ability_form');
  if ($ability_id)
  {
    $character_ability = getCharacterability($character_id, $ability_id);
    $form->setValues($character_ability);
    $title = 'Edit character ' . htmlWrap('em', $character['name']) . '\'s ability ' . htmlWrap('em', $abilities[$character_ability['ability_id']]);
  }
  else
  {
    $title = 'Add New ability to ' . htmlWrap('em', $character['name']);
  }
  $form->setTitle($title);

  $markup = htmlWrap('span', getCharacterProficiencyBonus($character_id), array('class' => array('pb')));
  $field = new FieldMarkup('pb', 'Proficiency Bonus', $markup);
  $form->addField($field);

  // Character.
  $field = new FieldHidden('character_id');
  $field->setValue($character_id);
  $form->addField($field);

  // Ability.
  if (!$ability_id)
  {
    $options = $abilities;
    foreach ($character_ability_map as $character_ability)
    {
      unset($options[$character_ability['ability_id']]);
    }
    if (empty($options))
    {
      $response['status'] = FALSE;
      $response['data'] = 'All abilities defined.';
      jsonResponseDie($response);
    }
    $field = new FieldSelect('ability_id', 'ability', $options);
    $form->addField($field);
  }
  else
  {
    $field = new FieldHidden('ability_id');
    $form->addField($field);
  }

  // Score.
  $field = new FieldNumber('score', 'Score');
  $field->setValue(8);
  $form->addField($field);

  // Proficiency Multiplier.
  $field = new FieldNumber('proficiency_multiplier', 'Saving Throw Proficiency Multiplier');
  $field->setValue(0);
  $form->addField($field);

  // Submit
  $value = 'Add';
  if ($ability_id)
  {
    $value = 'Update';
  }
  $field = new FieldSubmit('submit', $value);
  $form->addField($field);

  if ($ability_id)
  {
    $field = new FieldSubmit('delete', 'Delete');
    $form->addField($field);
  }

  $response['data'] = $form->__toString();
  jsonResponseDie($response);
}

function characterAbilityUpsertSubmitAjax()
{
  $response = getAjaxDefaultResponse();
  $character_ability = $_POST;

  if (isset($_POST['delete']))
  {
    deleteCharacterability($character_ability);
    $attr = array('query' => array('id' => $character_ability['character_id']));
    redirect('/character', $attr);
  }
  // Update.
  elseif ($_POST['operation'] == 'update')
  {
    updateCharacterAbility($character_ability);
    $response['data'] = 'Updated';
  }
  // Create.
  else
  {
    createCharacterAbility($character_ability);
    $response['data'] = 'created';
  }
  jsonResponseDie($response);
}

function characterAbilityListAjax()
{
  $response = getAjaxDefaultResponse();
  $character_id = getUrlID('character_id');

  $output = '';
  $abilities = getAbilityList();
  $character_ability_map = getCharacterAbilityList($character_id);
  foreach ($character_ability_map as $character_ability)
  {

    $row = array();
    $attr = array(
      'query' => array(
        'character_id' => $character_id,
        'ability_id' => $character_ability['ability_id'],
      ),
      'class' => 'ability',
    );
    $row[] = a($abilities[$character_ability['ability_id']], '/character/class', $attr);
    $row[] = $character_ability['score'];
    $modifier = getAbilityModifier($character_ability['score']);
    $row[] = $modifier;
    $row[] = $character_ability['proficiency_multiplier'];
    $row[] = $character_ability['proficiency_multiplier'] * getCharacterProficiencyBonus($character_id) + $modifier;
    $output .= TableTemplate::tableRow($row);
  }
  $response['data'] = $output;

  jsonResponseDie($response);
}

