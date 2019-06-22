<?php
/******************************************************************************
 *
 * Speed List
 *
 ******************************************************************************/
function speedList()
{
  $page = getUrlID('page', 1);
  $speeds = getSpeedPager($page);

  $template = new ListPageTemplate('Speeds');

  // Operations.
  $template->addOperation(a('New Speed', '/speed'));

  if ($page > 1)
  {
    $attr = array(
      'query' => array('page' => ($page - 1)),
    );
    $template->addOperation(a('Prev Page', '/speed', $attr));
  }

  if (count($speeds) >= PAGER_SIZE_DEFAULT)
  {
    $attr = array(
      'query' => array('page' => ($page + 1)),
    );
    $template->addOperation(a('Next Page', '/speed', $attr));
  }

  // List
  $table = new TableTemplate();
  $table->setAttr('class', array('speed-list'));
  $table->setHeader(array('Seconds', 'Casting Time', 'Duration', 'Description'));

  foreach ($speeds as $speed)
  {
    $row = array();
    $attr = array(
      'query' => array('id' => $speed['id'])
    );
    $row[] = a($speed['id'], '/speed', $attr);
    $row[] = $speed['casting_time'];
    $row[] = $speed['duration'];
    $row[] = $speed['description'];
    $table->addRow($row);
  }
  $template->setList($table);
  return $template;
}

/******************************************************************************
 *
 * Speed Upsert
 *
 ******************************************************************************/
function speedUpsertForm()
{
  $template = new FormPageTemplate();

  // Submit.
  if (isset($_SERVER['REQUEST_METHOD']) && ($_SERVER['REQUEST_METHOD'] == 'POST'))
  {
    $template->addMessage(speedUpsertSubmit());
  }

  $speed_id = getUrlID('id');

  $form = new Form('speed_form');
  $title = 'Add New Speed';
  $operation = 'create';
  if ($speed_id !== FALSE)
  {
    $speed = getSpeed($speed_id);
    $form->setValues($speed);
    $title = 'Edit speed ' . htmlWrap('em', $speed['name']);
    $operation = 'update';
  }
  $form->setTitle($title);

  // Operation.
  $field = new FieldHidden('operation');
  $field->setValue($operation);
  $form->addField($field);

  // ID.
  $field = new FieldText('id', 'Seconds');
  $form->addField($field);

  // Casting Time
  $field = new FieldText('casting_time', 'Casting Time (Time it takes to perform an action)');
  $form->addField($field);

  // Duration
  $field = new FieldText('duration', 'Duration (Amount of time something lasts)');
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

function speedUpsertSubmit()
{
  $speed = $_POST;

  if (isset($_POST['delete']))
  {
    deleteSpeed($speed['id']);
    redirect('/speeds');
  }

  // Update.
  if ($speed['operation'] === 'update')
  {
    updateSpeed($speed);
    return htmlWrap('h3', 'Speed ' . htmlWrap('em', $speed['name']) . ' (' . $speed['id'] . ') updated.');
  }
  // Create.
  else
  {
    // Speed id must be unique.
    $speeds = getSpeedDurationList() + getSpeedCastingTimeList();
    if ($speeds[$speed['id']])
    {
      return htmlWrap('h3', 'Error: Speed (seconds) already exists.');
    }

    $speed['id'] = createSpeed($speed);
    return htmlWrap('h3', 'Speed ' . htmlWrap('em', $speed['name']) . ' (' . $speed['id'] . ') created.');
  }
}
