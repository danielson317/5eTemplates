<?php

/******************************************************************************
 *
 * Ability List
 *
 ******************************************************************************/
function abilityList()
{
  $page = getUrlID('page', 1);
  $abilities = getAbilityPager($page);

  $template = new ListPageTemplate('Abilities');

  // Operations.
  $template->addOperation(a('New Ability', '/ability'));

  if ($page > 1)
  {
    $attr = array(
      'query' => array('page' => ($page - 1)),
    );
    $template->addOperation(a('Prev Page', '/ability', $attr));
  }

  if (count($abilities) >= PAGER_SIZE_DEFAULT)
  {
    $attr = array(
      'query' => array('page' => ($page + 1)),
    );
    $template->addOperation(a('Next Page', '/ability', $attr));
  }

  // List
  $table = new TableTemplate();
  $table->setAttr('class', array('ability-list'));
  $table->setHeader(array('Name', 'Code', 'Description'));

  foreach ($abilities as $ability)
  {
    $row = array();
    $attr = array(
      'query' => array('id' => $ability['id']),
    );
    $row[] = a($ability['name'], '/ability', $attr);
    $row[] = $ability['code'];
    $row[] = $ability['description'];
    $table->addRow($row);
  }
  $template->setList($table);
  return $template;
}

/******************************************************************************
 *
 * Ability Upsert
 *
 ******************************************************************************/
function abilityUpsertForm()
{
  $template = new FormPageTemplate();

  // Submit.
  if (isset($_SERVER['REQUEST_METHOD']) && ($_SERVER['REQUEST_METHOD'] == 'POST'))
  {
    $template->addMessage(abilityUpsertSubmit());
  }

  $ability_id = getUrlID('id');

  $form = new Form('ability_form');
  $title = 'Add New Ability';
  if ($ability_id)
  {
    $ability = getAbility($ability_id);
    $form->setValues($ability);
    $title = 'Edit ability ' . htmlWrap('em', $ability['name']);
  }
  $form->setTitle($title);

  // ID.
  $field = new FieldHidden('id');
  $form->addField($field);

  // Name
  $field = new FieldText('name', 'Name');
  $form->addField($field);

  // Code
  $field = new FieldText('code', 'Code');
  $form->addField($field);

  // Description
  $field = new FieldTextarea('description', 'Description');
  $form->addField($field);

  // Submit
  $value = 'Add';
  if ($ability_id)
  {
    $value = 'Update';
  }
  $field = new FieldSubmit('submit', $value);
  $form->addField($field);

  // Delete.
  if ($ability_id)
  {
    $field = new FieldSubmit('delete', 'Delete');
    $form->addField($field);
  }

  // Template.
  $template->setForm($form);
  return $template;
}

function abilityUpsertSubmit()
{
  $ability = $_POST;
  unset($ability['submit']);

  if (isset($_POST['delete']))
  {
    deleteAbility($_POST['id']);
    redirect('/ability');
  }

  // Update.
  if ($_POST['id'])
  {
    updateAbility($ability);
    return htmlWrap('h3', 'Ability ' . htmlWrap('em', $ability['name']) . ' (' . $ability['id'] . ') updated.');
  }
  // Create.
  else
  {
    unset($ability['id']);
    $ability['id'] = createAbility($ability);
    return htmlWrap('h3', 'Ability ' . htmlWrap('em', $ability['name']) . ' (' . $ability['id'] . ') created.');
  }
}
