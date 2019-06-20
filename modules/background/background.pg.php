<?php
/******************************************************************************
 *
 * Background List
 *
 ******************************************************************************/
function backgroundList()
{
  $page = getUrlID('page', 1);
  $backgrounds = getBackgroundPager($page);

  $template = new ListPageTemplate('Backgrounds');

  // Operations.
  $template->addOperation(a('New Background', '/background'));

  if ($page > 1)
  {
    $attr = array(
      'query' => array('page' => ($page - 1)),
    );
    $template->addOperation(a('Prev Page', '/background', $attr));
  }

  if (count($backgrounds) >= DEFAULT_PAGER_SIZE)
  {
    $attr = array(
      'query' => array('page' => ($page + 1)),
    );
    $template->addOperation(a('Next Page', '/background', $attr));
  }

  // List
  $table = new TableTemplate();
  $table->setAttr('class', array('background-list'));
  $table->setHeader(array('Name', 'Description'));

  foreach ($backgrounds as $background)
  {
    $row = array();
    $attr = array(
      'query' => array('id' => $background['id'])
    );
    $row[] = a($background['name'], '/background', $attr);
    $row[] = $background['description'];
    $table->addRow($row);
  }
  $template->setList($table);
  return $template;
}

/******************************************************************************
 *
 * Background Upsert
 *
 ******************************************************************************/
function backgroundUpsertForm()
{
  $template = new FormPageTemplate();

  // Submit.
  if (isset($_SERVER['REQUEST_METHOD']) && ($_SERVER['REQUEST_METHOD'] == 'POST'))
  {
    $template->addMessage(backgroundUpsertSubmit());
  }

  $background_id = getUrlID('id');

  $form = new Form('background_form');
  $title = 'Add New Background';
  if ($background_id)
  {
    $background = getBackground($background_id);
    $form->setValues($background);
    $title = 'Edit background ' . htmlWrap('em', $background['name']);
  }
  $form->setTitle($title);

  // ID.
  $field = new FieldHidden('id');
  $form->addField($field);

  // Name
  $field = new FieldText('name', 'Name');
  $form->addField($field);

  // Description.
  $field = new FieldTextarea('description', 'Description');
  $form->addField($field);

  // Submit
  $value = 'Add';
  if ($background_id)
  {
    $value = 'Update';
  }
  $field = new FieldSubmit('submit', $value);
  $form->addField($field);

  // Delete.
  if ($background_id)
  {
    $field = new FieldSubmit('delete', 'Delete');
    $form->addField($field);
  }

  // Template.
  $template->setForm($form);
  return $template;
}

function backgroundUpsertSubmit()
{
  $background = $_POST;
  unset($background['submit']);

  if (isset($_POST['delete']))
  {
    deleteBackground($background['id']);
    redirect('/backgrounds');
  }

  // Update.
  if ($background['id'])
  {
    updateBackground($background);
    return htmlWrap('h3', 'Background ' . htmlWrap('em', $background['name']) . ' (' . $background['id'] . ') updated.');
  }
  // Create.
  else
  {
    unset($background['id']);
    $background['id'] = createBackground($background);
    return htmlWrap('h3', 'Background ' . htmlWrap('em', $background['name']) . ' (' . $background['id'] . ') created.');
  }
}
