<?php

/******************************************************************************
 *
 * Skill List
 *
 ******************************************************************************/
function skillList()
{
  $page = getUrlID('page', 1);
  $skills = getSkillPager($page);

  $template = new ListPageTemplate('Skills');

  // Operations.
  $template->addOperation(a('New Skill', 'skill'));

  if ($page > 1)
  {
    $attr = array(
      'query' => array('page' => ($page - 1)),
    );
    $template->addOperation(a('Prev Page', '/skill', $attr));
  }

  if (count($skills) >= PAGER_SIZE_DEFAULT)
  {
    $attr = array(
      'query' => array('page' => ($page + 1)),
    );
    $template->addOperation(a('Next Page', '/skill', $attr));
  }

  // List
  $table = new TableTemplate();
  $table->setAttr('class', array('skill-list'));
  $table->setHeader(array('Name', 'Code', 'ability', 'Description'));

  $abilities = getAbilityList();
  foreach ($skills as $skill)
  {
    $row = array();
    $attr = array(
      'query' => array('id' => $skill['id']),
    );
    $row[] = a($skill['name'], '/skill', $attr);
    $row[] = $skill['code'];
    $row[] = $abilities[$skill['ability_id']];
    $row[] = $skill['description'];
    $table->addRow($row);
  }
  $template->setList($table);
  return $template;
}


/******************************************************************************
 *
 * Skill Upsert
 *
 ******************************************************************************/
function skillUpsertForm()
{
  $template = new FormPageTemplate();

  // Submit.
  if (isset($_SERVER['REQUEST_METHOD']) && ($_SERVER['REQUEST_METHOD'] == 'POST'))
  {
    $template->addMessage(skillUpsertSubmit());
  }

  $skill_id = getUrlID('id');

  $form = new Form('skill_form');
  $title = 'Add New skill';
  if ($skill_id)
  {
    $skill = getSkill($skill_id);
    $form->setValues($skill);
    $title = 'Edit skill ' . htmlWrap('em', $skill['name']);
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

  // ability
  $options = array(0 => '---Select One---') + getAbilityList();
  $field = new FieldSelect('ability_id', 'Parent ability', $options);
  $form->addField($field);

  // Description
  $field = new FieldTextarea('description', 'Description');
  $form->addField($field);

  // Submit
  $value = 'Add';
  if ($skill_id)
  {
    $value = 'Update';
  }
  $field = new FieldSubmit('submit', $value);
  $form->addField($field);

  // Delete.
  if ($skill_id)
  {
    $field = new FieldSubmit('delete', 'Delete');
    $form->addField($field);
  }

  // Template.
  $template->setForm($form);
  return $template;
}

function skillUpsertSubmit()
{
  $skill = $_POST;
  unset($skill['submit']);

  if (isset($_POST['delete']))
  {
    deleteSkill($skill['id']);
    redirect('/skills');
  }

  // Update.
  if ($_POST['id'])
  {
    updateSkill($skill);
    return htmlWrap('h3', 'Skill ' . htmlWrap('em', $skill['name']) . ' (' . $skill['id'] . ') updated.');
  }
  // Create.
  else
  {
    unset($skill['id']);
    $skill['id'] = createSkill($skill);
    return htmlWrap('h3', 'Skill ' . htmlWrap('em', $skill['name']) . ' (' . $skill['id'] . ') created.');
  }
}
