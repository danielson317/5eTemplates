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

  $template = new ListPageTemplate('Class');

  // Operations.
  $template->addOperation(a('New Class', '/class'));

  if ($page > 1)
  {
    $attr = array(
      'query' => array('page' => ($page - 1)),
    );
    $template->addOperation(a('Prev Page', '/class', $attr));
  }

  if (count($classes) >= DEFAULT_PAGER_SIZE)
  {
    $attr = array(
      'query' => array('page' => ($page + 1)),
    );
    $template->addOperation(a('Next Page', '/class', $attr));
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
      'query' => array('id' => $class['id']),
    );
    $row[] = a($class['name'], '/class', $attr);
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
  $template = new FormPageTemplate();

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
        'query' => array('id' => $subclass['id']),
      );
      $row[] = a($subclass['name'], '/subclass', $attr);
      $row[] = $subclass['description'];
      $table->addRow($row);
    }

    $attr = array(
      'query' => array('class_id' => $class['id']),
    );
    $link = a('Add New Subclass', '/subclass', $attr);

    $field = new FieldMarkup('subclasses', '', $table . $link);
    $form->addField($field);
  }

  // Source.
  $options = array(0 => '--Select One--') + getSourceList();
  $field = new FieldSelect('source_id', 'Source', $options);
  $form->addField($field);

  // Source location (page number).
  $field = new FieldNumber('source_location', 'Page');
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
