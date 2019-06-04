<?php

/******************************************************************************
 *
 * Subclass List
 *
 ******************************************************************************/
function subclassAjax()
{
  $class_id = getUrlID('class_id');

  $list = array(0 => '--Select One--') + getSubclassList($class_id);

  die(optionList($list));
}

/******************************************************************************
 *
 * Subclass Upsert
 *
 ******************************************************************************/
function subclassUpsertForm()
{
  $template = new FormPageTemplate();

  // Submit.
  if (isset($_SERVER['REQUEST_METHOD']) && ($_SERVER['REQUEST_METHOD'] == 'POST'))
  {
    $template->addMessage(subclassUpsertSubmit());
  }

  $subclass_id = getUrlID('id');

  $form = new Form('subclass_form');
  if ($subclass_id)
  {
    $subclass = getSubclass($subclass_id);
    $class_id = $subclass['class_id'];
    $class = getClass($subclass['class_id']);
    $form->setValues($subclass);
    $title = 'Edit subclass ' . htmlWrap('em', $subclass['name']) . ' of class ' . htmlWrap('em', $class['name']);
  }
  else
  {
    $class_id = getUrlID('class_id');
    $class = getClass($class_id);
    $title = 'Add new subclass to class ' . htmlWrap('em', $class['name']);
  }
  $form->setTitle($title);

  // ID.
  $field = new FieldHidden('id');
  $form->addField($field);

  // Class ID.
  $field = new FieldHidden('class_id');
  $field->setValue($class['id']);
  $form->addField($field);

  // Sub classes.
  $subclasses = getSubclassPager($class_id);
  $table = new TableTemplate('subclasses');
  $table->setHeader(array('Subclass', 'Description'));
  foreach ($subclasses as $subclass)
  {
    $row = array();
    $attr = array(
      'query' => array('id'=> $subclass['id']),
    );
    $row[] = a($subclass['name'], '/sublcass', $attr);
    $row[] = $subclass['description'];
    $table->addRow($row);
  }

  $link = '';
  if ($subclass_id)
  {
    $attr = array(
      'query' => array('class_id' => $class['id']),
    );
    $link = a('Add New Subclass', '/subclass', $attr);
  }

  $field = new FieldMarkup('subclasses', '', $table . $link);
  $form->addField($field);

  // Name
  $field = new FieldText('name', 'Name');
  $form->addField($field);

  // Description.
  $field = new FieldTextarea('description', 'Description');
  $form->addField($field);

  // Source.
  $options = array(0 => '--Select One--') + getSourceList();
  $field = new FieldSelect('source_id', 'Source', $options);
  $form->addField($field);

  // Back link.
  $attr = array(
    'query' => array('id' => $class['id']),
  );
  $link = a('Back to class ' . $class['name'], '/class', $attr);

  $field = new FieldMarkup('links', '', $link);
  $form->addField($field);

  // Submit
  $value = 'Add';
  if ($subclass_id)
  {
    $value = 'Update';
  }
  $field = new FieldSubmit('submit', $value);
  $form->addField($field);

  // Delete.
  $field = new FieldSubmit('delete', 'Delete');
  $form->addField($field);

  // Template.
  $template->setForm($form);
  return $template;
}

function subclassUpsertSubmit()
{
  $subclass = $_POST;
  unset($subclass['submit']);

  // Delete.
  if (isset($_POST['delete']))
  {
    deleteSubclass($subclass['id']);
    $attr = array('query' => array('id' => $subclass['class_id']));
    redirect('/class', $attr);
  }

  // Update.
  if ($_POST['id'])
  {
    unset($subclass['class_id']);
    updateSubclass($subclass);
    return htmlWrap('h3', 'Sublass ' . htmlWrap('em', $subclass['name']) . ' (' . $subclass['id'] . ') updated.');
  }
  // Create.
  else
  {
    unset($subclass['id']);
    $subclass['id'] = createSubclass($subclass);
    return htmlWrap('h3', 'Subclass ' . htmlWrap('em', $subclass['name']) . ' (' . $subclass['id'] . ') created.');
  }
}
