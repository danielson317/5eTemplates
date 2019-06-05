<?php
/******************************************************************************
 *
 * Die List
 *
 ******************************************************************************/
function dieList()
{
  $page = getUrlID('page', 1);
  $dice = getDiePager($page);

  $template = new ListPageTemplate('Dice');

  // Operations.
  $template->addOperation(a('New Die', '/die'));

  if ($page > 1)
  {
    $attr = array(
      'query' => array('page' => ($page - 1)),
    );
    $template->addOperation(a('Prev Page', '/die', $attr));
  }

  if (count($dice) >= DEFAULT_PAGER_SIZE)
  {
    $attr = array(
      'query' => array('page' => ($page + 1)),
    );
    $template->addOperation(a('Next Page', '/die', $attr));
  }

  // List
  $table = new TableTemplate();
  $table->setAttr('class', array('die-list'));
  $table->setHeader(array('Name', 'Description'));

  foreach ($dice as $die)
  {
    $row = array();
    $attr = array(
      'query' => array('id' => $die['id'])
    );
    $row[] = a($die['name'], '/die', $attr);
    $row[] = $die['description'];
    $table->addRow($row);
  }
  $template->setList($table);
  return $template;
}

/******************************************************************************
 *
 * Die Upsert
 *
 ******************************************************************************/
function dieUpsertForm()
{
  $template = new FormPageTemplate();

  // Submit.
  if (isset($_SERVER['REQUEST_METHOD']) && ($_SERVER['REQUEST_METHOD'] == 'POST'))
  {
    $template->addMessage(dieUpsertSubmit());
  }

  $die_id = getUrlID('id');

  $form = new Form('die_form');
  $title = 'Add New Die';
  $operation = 'create';
  if ($die_id !== FALSE)
  {
    $die = getDie($die_id);
    $form->setValues($die);
    $title = 'Edit die ' . htmlWrap('em', $die['name']);
    $operation = 'update';
  }
  $form->setTitle($title);

  // Operation.
  $field = new FieldHidden('operation');
  $field->setValue($operation);
  $form->addField($field);

  // ID.
  $field = new FieldText('id', 'ID');
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

function dieUpsertSubmit()
{
  $die = $_POST;

  if (isset($_POST['delete']))
  {
    deleteDie($die['id']);
    redirect('/dice');
  }

  // Update.
  if ($die['operation'] === 'update')
  {
    updateDie($die);
    return htmlWrap('h3', 'Die ' . htmlWrap('em', $die['name']) . ' (' . $die['id'] . ') updated.');
  }
  // Create.
  else
  {
    // Die id must be unique.
    $dice = getDieList();
    if ($dice[$die['id']])
    {
      return htmlWrap('h3', 'Error: Distance already exists.');
    }

    $die['id'] = createDie($die);
    return htmlWrap('h3', 'Die ' . htmlWrap('em', $die['name']) . ' (' . $die['id'] . ') created.');
  }
}
