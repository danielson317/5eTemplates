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
  $template->addOperation(a('New Attribute', '/attribute'));

  if ($page > 1)
  {
    $attr = array(
      'query' => array('page' => ($page - 1)),
    );
    $template->addOperation(a('Prev Page', '/attribute', $attr));
  }

  if (count($attributes) >= DEFAULT_PAGER_SIZE)
  {
    $attr = array(
      'query' => array('page' => ($page + 1)),
    );
    $template->addOperation(a('Next Page', '/attribute', $attr));
  }

  // List
  $table = new TableTemplate();
  $table->setAttr('class', array('attribute-list'));
  $table->setHeader(array('Name', 'Code', 'Description'));

  foreach ($attributes as $attribute)
  {
    $row = array();
    $attr = array(
      'query' => array('id' => $attribute['id']),
    );
    $row[] = a($attribute['name'], '/attribute', $attr);
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
    redirect('/attribute');
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
