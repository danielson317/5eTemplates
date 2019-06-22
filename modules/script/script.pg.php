<?php
/******************************************************************************
 *
 * Script List
 *
 ******************************************************************************/
function scriptList()
{
  $page = getUrlID('page', 1);
  $scripts = getScriptPager($page);

  $template = new ListPageTemplate('Scripts');

  // Operations.
  $template->addOperation(a('New Script', '/script'));

  if ($page > 1)
  {
    $attr = array(
      'query' => array('page' => ($page - 1)),
    );
    $template->addOperation(a('Prev Page', '/script', $attr));
  }

  if (count($scripts) >= PAGER_SIZE_DEFAULT)
  {
    $attr = array(
      'query' => array('page' => ($page + 1)),
    );
    $template->addOperation(a('Next Page', '/script', $attr));
  }

  // List
  $table = new TableTemplate();
  $table->setAttr('class', array('script-list'));
  $table->setHeader(array('Name', 'Description'));

  foreach ($scripts as $script)
  {
    $row = array();
    $attr = array(
      'query' => array('id' => $script['id'])
    );
    $row[] = a($script['name'], '/script', $attr);
    $row[] = $script['description'];
    $table->addRow($row);
  }
  $template->setList($table);
  return $template;
}

/******************************************************************************
 *
 * Script Upsert
 *
 ******************************************************************************/
function scriptUpsertForm()
{
  $template = new FormPageTemplate();

  // Submit.
  if (isset($_SERVER['REQUEST_METHOD']) && ($_SERVER['REQUEST_METHOD'] == 'POST'))
  {
    $template->addMessage(scriptUpsertSubmit());
  }

  $script_id = getUrlID('id');

  $form = new Form('script_form');
  $title = 'Add New Script';
  if ($script_id)
  {
    $script = getScript($script_id);
    $form->setValues($script);
    $title = 'Edit script ' . htmlWrap('em', $script['name']);
  }
  $form->setTitle($title);

  // ID.
  $field = new FieldHidden('id');
  $form->addField($field);

  // Name
  $field = new FieldText('name', 'Name');
  $form->addField($field);

  // Source
  $options = array(0 => '--Select One--') + getSourceDetailList();
  $field = new FieldSelect('source_id', 'Source', $options);
  $form->addField($field);

  // Description.
  $field = new FieldTextarea('description', 'Description');
  $form->addField($field);

  // Submit
  $value = 'Add';
  if ($script_id)
  {
    $value = 'Update';
  }
  $field = new FieldSubmit('submit', $value);
  $form->addField($field);

  // Delete.
  if ($script_id)
  {
    $field = new FieldSubmit('delete', 'Delete');
    $form->addField($field);
  }

  // Template.
  $template->setForm($form);
  return $template;
}

function scriptUpsertSubmit()
{
  $script = $_POST;
  unset($script['submit']);

  if (isset($_POST['delete']))
  {
    deleteScript($script['id']);
    redirect('/scripts');
  }

  // Update.
  if ($script['id'])
  {
    updateScript($script);
    return htmlWrap('h3', 'Script ' . htmlWrap('em', $script['name']) . ' (' . $script['id'] . ') updated.');
  }
  // Create.
  else
  {
    unset($script['id']);
    $script['id'] = createScript($script);
    return htmlWrap('h3', 'Script ' . htmlWrap('em', $script['name']) . ' (' . $script['id'] . ') created.');
  }
}
