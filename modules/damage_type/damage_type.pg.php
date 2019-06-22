<?php

/******************************************************************************
 *
 * Damage Types
 *
 ******************************************************************************/
function damageTypeList()
{
  $page = getUrlID('page', 1);
  $damage_types = getDamageTypePager($page);

  $template = new ListPageTemplate('Damage Types');
  $template->addCssFilePath('/themes/default/css/item.css');

  // Operations.
  $attr = array(
    'href' => 'damage-type',
  );
  $template->addOperation(htmlWrap('a', 'New Damage Type', $attr));

  if ($page > 1)
  {
    $attr = array(
      'href' => '?page=' . ($page - 1),
    );
    $template->addOperation(htmlWrap('a', 'Prev Page', $attr));
  }

  if (count($damage_types) >= PAGER_SIZE_DEFAULT)
  {
    $attr = array(
      'href' => '?page=' . ($page + 1),
    );
    $template->addOperation(htmlWrap('a', 'Next Page', $attr));
  }

  // List
  $table = new TableTemplate();
  $table->setAttr('class', array('damage-type-list'));
  $table->setHeader(array('Name', 'Code', 'Description'));

  foreach($damage_types as $damage_type)
  {
    $row = array();
    $attr = array(
      'href' => 'damage-type?id=' . $damage_type['id'],
    );
    $row[] = htmlWrap('a', $damage_type['name'], $attr);
    $row[] = $damage_type['code'];
    $row[] = $damage_type['description'];
    $table->addRow($row);
  }
  $template->setList($table);
  return $template;
}

function damageTypeUpsertForm()
{
  $template = new FormPageTemplate();
  $template->addCssFilePath('/themes/default/css/item.css');

  // Submit.
  if (isset($_SERVER['REQUEST_METHOD']) && ($_SERVER['REQUEST_METHOD'] == 'POST'))
  {
    $template->addMessage(damageTypeUpsertSubmit());
  }

  $damage_id = getUrlID('id');

  $form = new Form('damage_type_form');
  $title = 'Add New Damage Type';
  if ($damage_id)
  {
    $damage = getDamageType($damage_id);
    $form->setValues($damage);
    $title = 'Edit damage type ' . htmlWrap('em', $damage['name']);
  }
  $form->setTitle($title);

  // ID.
  $field = new FieldHidden('id');
  $form->addField($field);

  // Name.
  $field = new FieldText('name', 'Name');
  $form->addField($field);

  // Name.
  $field = new FieldText('code', 'Code (2-3 characters)');
  $form->addField($field);

  // Description.
  $field = new FieldTextarea('description', 'Description');
  $form->addField($field);

  // Submit
  $value = 'Create';
  if ($damage_id)
  {
    $value = 'Update';
  }
  $field = new FieldSubmit('submit', $value);
  $form->addField($field);

  $template->setForm($form);

  return $template;
}

function damageTypeUpsertSubmit()
{
  $damage_type = $_POST;
  unset($damage_type['submit']);

  if ($damage_type['id'])
  {
    updateDamageType($damage_type);
    return htmlWrap('h3', 'Damage type ' . htmlWrap('em', $damage_type['name']) . ' (' . $damage_type['id'] . ') updated.');
  }
  else
  {
    unset($damage_type['id']);
    $damage_type['id'] = createDamageType($damage_type);
    return htmlWrap('h3', 'New damage type' . htmlWrap('em', $damage_type['name']) . ' (' . $damage_type['id'] . ') created.');
  }
}
