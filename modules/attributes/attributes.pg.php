<?php

/******************************************************************************
 *
 * Attribute List
 *
 ******************************************************************************/
function attributeList()
{
  $page = getUrlID('page', 1);
  $attributes = getAttributePager($page);

  $template = new ListPageTemplate('Attributes');

  // Operations.
  $attr = array(
    'href' => 'attribute',
  );
  $template->addOperation(htmlWrap('a', 'New Attribute', $attr));

  if ($page > 1)
  {
    $attr = array(
      'href' => '?page=' . ($page - 1),
    );
    $template->addOperation(htmlWrap('a', 'Prev Page', $attr));
  }

  if (count($attributes) >= DEFAULT_PAGER_SIZE)
  {
    $attr = array(
      'href' => '?page=' . ($page + 1),
    );
    $template->addOperation(htmlWrap('a', 'Next Page', $attr));
  }

  // List
  $table = new TableTemplate();
  $table->setAttr('class', array('attribute-list'));
  $table->setHeader(array('Name', 'Code', 'Description'));

  foreach ($attributes as $attribute)
  {
    $row = array();
    $attr = array(
      'href' => '/attribute?id=' . $attribute['id'],
    );
    $row[] = htmlWrap('a', $attribute['name'], $attr);
    $row[] = $attribute['code'];
    $row[] = $attribute['description'];
    $table->addRow($row);
  }
  $template->setList($table);
  return $template;
}

/******************************************************************************
 *
 * Attribute Upsert
 *
 ******************************************************************************/
function attributeUpsertForm()
{
  $template = new FormPageTemplate();

  // Submit.
  if (isset($_SERVER['REQUEST_METHOD']) && ($_SERVER['REQUEST_METHOD'] == 'POST'))
  {
    $template->addMessage(attributeUpsertSubmit());
  }

  $attribute_id = getUrlID('id');

  $form = new Form('attribute_form');
  $title = 'Add New Attribute';
  if ($attribute_id)
  {
    $attribute = getAttribute($attribute_id);
    $form->setValues($attribute);
    $title = 'Edit attribute ' . htmlWrap('em', $attribute['name']);
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
  if ($attribute_id)
  {
    $value = 'Update';
  }
  $field = new FieldSubmit('submit', $value);
  $form->addField($field);

  // Delete.
  if ($attribute_id)
  {
    $field = new FieldSubmit('delete', 'Delete');
    $form->addField($field);
  }

  // Template.
  $template->setForm($form);
  return $template;
}

function attributeUpsertSubmit()
{
  $attribute = $_POST;
  unset($attribute['submit']);

  if (isset($_POST['delete']))
  {
    deleteAttribute($_POST['id']);
    redirect('/attributes');
  }

  // Update.
  if ($_POST['id'])
  {
    updateAttribute($attribute);
    return htmlWrap('h3', 'Attribute ' . htmlWrap('em', $attribute['name']) . ' (' . $attribute['id'] . ') updated.');
  }
  // Create.
  else
  {
    unset($attribute['id']);
    $attribute['id'] = createAttribute($attribute);
    return htmlWrap('h3', 'Attribute ' . htmlWrap('em', $attribute['name']) . ' (' . $attribute['id'] . ') created.');
  }
}

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
  $attr = array(
    'href' => 'skill',
  );
  $template->addOperation(htmlWrap('a', 'New Skill', $attr));

  if ($page > 1)
  {
    $attr = array(
      'href' => '?page=' . ($page - 1),
    );
    $template->addOperation(htmlWrap('a', 'Prev Page', $attr));
  }

  if (count($skills) >= DEFAULT_PAGER_SIZE)
  {
    $attr = array(
      'href' => '?page=' . ($page + 1),
    );
    $template->addOperation(htmlWrap('a', 'Next Page', $attr));
  }

  // List
  $table = new TableTemplate();
  $table->setAttr('class', array('skill-list'));
  $table->setHeader(array('Name', 'Code', 'Attribute', 'Description'));

  $attributes = getAttributeList();
  foreach ($skills as $skill)
  {
    $row = array();
    $attr = array(
      'href' => '/skill?id=' . $skill['id'],
    );
    $row[] = htmlWrap('a', $skill['name'], $attr);
    $row[] = $skill['code'];
    $row[] = $attributes[$skill['attribute_id']];
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

  // Attribute
  $options = array(0 => '---Select One---') + getAttributeList();
  $field = new FieldSelect('attribute_id', 'Parent Attribute', $options);
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