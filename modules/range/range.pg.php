<?php
/******************************************************************************
 *
 * Range List
 *
 ******************************************************************************/
function rangeList()
{
  $page = getUrlID('page', 1);
  $ranges = getRangePager($page);

  $template = new ListPageTemplate('Ranges');

  // Operations.
  $template->addOperation(a('New Range', '/range'));

  if ($page > 1)
  {
    $attr = array(
      'query' => array('page' => ($page - 1)),
    );
    $template->addOperation(a('Prev Page', '/range', $attr));
  }

  if (count($ranges) >= DEFAULT_PAGER_SIZE)
  {
    $attr = array(
      'query' => array('page' => ($page + 1)),
    );
    $template->addOperation(a('Next Page', '/range', $attr));
  }

  // List
  $table = new TableTemplate();
  $table->setAttr('class', array('range-list'));
  $table->setHeader(array('Name', 'Description'));

  foreach ($ranges as $range)
  {
    $row = array();
    $attr = array(
      'query' => array('id' => $range['id'])
    );
    $row[] = a($range['name'], '/range', $attr);
    $row[] = $range['description'];
    $table->addRow($row);
  }
  $template->setList($table);
  return $template;
}

/******************************************************************************
 *
 * Range Upsert
 *
 ******************************************************************************/
function rangeUpsertForm()
{
  $template = new FormPageTemplate();

  // Submit.
  if (isset($_SERVER['REQUEST_METHOD']) && ($_SERVER['REQUEST_METHOD'] == 'POST'))
  {
    $template->addMessage(rangeUpsertSubmit());
  }

  $range_id = getUrlID('id');

  $form = new Form('range_form');
  $title = 'Add New Range';
  $operation = 'create';
  if ($range_id !== FALSE)
  {
    $range = getRange($range_id);
    $form->setValues($range);
    $title = 'Edit range ' . htmlWrap('em', $range['name']);
    $operation = 'update';
  }
  $form->setTitle($title);

  // Operation.
  $field = new FieldHidden('operation');
  $field->setValue($operation);
  $form->addField($field);

  // ID.
  $field = new FieldText('id', 'Distance (in character speed units)');
  $form->addField($field);

  // Name
  $field = new FieldText('name', 'Name');
  $form->addField($field);

  // Description.
  $field = new FieldTextarea('description', 'Description');
  $form->addField($field);

  // Submit
  $value = 'Add';
  if ($operation === 'update')
  {
    $value = 'Update';
  }
  $field = new FieldSubmit('submit', $value);
  $form->addField($field);

  // Delete.
  if ($operation === 'update')
  {
    $field = new FieldSubmit('delete', 'Delete');
    $form->addField($field);
  }

  // Template.
  $template->setForm($form);
  return $template;
}

function rangeUpsertSubmit()
{
  $range = $_POST;

  if (isset($_POST['delete']))
  {
    deleteRange($range['id']);
    redirect('/ranges');
  }

  // Update.
  if ($range['operation'] === 'update')
  {
    updateRange($range);
    return htmlWrap('h3', 'Range ' . htmlWrap('em', $range['name']) . ' (' . $range['id'] . ') updated.');
  }
  // Create.
  else
  {
    // Range id must be unique.
    $ranges = getRangeList();
    if ($ranges[$range['id']])
    {
      return htmlWrap('h3', 'Error: Distance already exists.');
    }

    $range['id'] = createRange($range);
    return htmlWrap('h3', 'Range ' . htmlWrap('em', $range['name']) . ' (' . $range['id'] . ') created.');
  }
}
