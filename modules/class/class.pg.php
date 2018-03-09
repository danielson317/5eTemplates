<?php

/******************************************************************************
 *
 *  Class List
 *
 ******************************************************************************/
function classList()
{
  $page = getUrlID('page', 1);
  $classes = getClassPager($page);

  $template = new ListTemplate('Class');

  // Operations.
  $attr = array(
    'href' => 'class',
  );
  $template->addOperation(htmlWrap('a', 'New Class', $attr));

  if ($page > 1)
  {
    $attr = array(
      'href' => '?page=' . ($page - 1),
    );
    $template->addOperation(htmlWrap('a', 'Prev Page', $attr));
  }

  if (count($classes) >= DEFAULT_PAGER_SIZE)
  {
    $attr = array(
      'href' => '?page=' . ($page + 1),
    );
    $template->addOperation(htmlWrap('a', 'Next Page', $attr));
  }

  // List
  $table = new TableTemplate();
  $table->setAttr('class', array('class-list'));
  $table->setHeader(array('Name', 'Hit Die'));

  $dice = getDiceList();
  foreach ($classes as $class)
  {
    $row = array();
    $attr = array(
      'href' => '/class?id=' . $class['id'],
    );
    $row[] = htmlWrap('a', $class['name'], $attr);
    $row[] = $dice[$class['hit_die']];
    $table->addRow($row);
  }
  $template->setList($table);
  return $template;
}

/******************************************************************************
 *
 * Class Upsert
 *
 ******************************************************************************/
function classUpsertForm()
{
  $template = new FormTemplate();

  // Submit.
  if (isset($_SERVER['REQUEST_METHOD']) && ($_SERVER['REQUEST_METHOD'] == 'POST'))
  {
    $template->addMessage(classUpsertSubmit());
  }

  $class_id = getUrlID('id');

  $form = new Form('class_form');
  $title = 'Add new class';
  if ($class_id)
  {
    $class = getClass($class_id);
    $form->setValues($class);
    $title = 'Edit class ' . htmlWrap('em', $class['name']);
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

  // Hit Die
  $options = array(0 => '--Select One--') + getDiceList();
  $field = new FieldSelect('hit_die', 'Hit Die', $options);
  $form->addField($field);

  // Saving Throw Proficiency 1
  $options = array(0 => '--Select One--') + getAttributeList();
  $field = new FieldSelect('stp1', 'Saving Throw 1', $options);
  $form->addField($field);

  // Saving Throw Proficiency 2.
  $options = array(0 => '--Select One--') + getAttributeList();
  $field = new FieldSelect('stp2', 'Saving Throw 2', $options);
  $form->addField($field);

  // Subclass Name
  $field = new FieldText('subclass_name', 'Subclass Name');
  $form->addField($field);

  // Subclasses
  if ($class_id)
  {
    $subclasses = getSubclassPager($class_id);

    $table = new TableTemplate('subclasses');
    $table->setHeader(array('Subclass', 'Description'));
    foreach ($subclasses as $subclass)
    {
      $row = array();
      $attr = array(
        'href' => '/subclass?id=' . $subclass['id'],
      );
      $row[] = htmlWrap('a', $subclass['name'], $attr);
      $row[] = $subclass['description'];
      $table->addRow($row);
    }

    $attr = array(
      'href' => '/subclass?class_id=' . $class['id'],
    );
    $link = htmlWrap('a', 'Add New Subclass', $attr);

    $field = new FieldMarkup('subclasses', '', $table . $link);
    $form->addField($field);
  }

  // Source.
  $options = array(0 => '--Select One--') + getSourceList();
  $field = new FieldSelect('source_id', 'Source', $options);
  $form->addField($field);

  // Submit
  $value = 'Add';
  if ($class_id)
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

function classUpsertSubmit()
{
  if (isset($_POST['delete']))
  {
    deleteClass($_POST['id']);
    redirect('/classes');
  }

  $class = $_POST;
  unset($class['submit']);

  // Update.
  if ($_POST['id'])
  {
    updateClass($class);
    return htmlWrap('h3', 'Class ' . htmlWrap('em', $class['name']) . ' (' . $class['id'] . ') updated.');
  }
  // Create.
  else
  {
    unset($class['id']);
    $class['id'] = createClass($class);
    return htmlWrap('h3', 'Class ' . htmlWrap('em', $class['name']) . ' (' . $class['id'] . ') created.');
  }
}

/******************************************************************************
 *
 * Subclass Upsert
 *
 ******************************************************************************/
function subclassUpsertForm()
{
  $template = new FormTemplate();

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
      'href' => '/subclass?id=' . $subclass['id'],
    );
    $row[] = htmlWrap('a', $subclass['name'], $attr);
    $row[] = $subclass['description'];
    $table->addRow($row);
  }

  $link = '';
  if ($subclass_id)
  {
    $attr = array(
      'href' => '/subclass?class_id=' . $class['id'],
    );
    $link = htmlWrap('a', 'Add New Subclass', $attr);
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
    'href' => '/class?id=' . $class['id'],
  );
  $link = htmlWrap('a', 'Back to class ' . $class['name'], $attr);

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
    redirect('/class?id=' . $subclass['class_id']);
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
