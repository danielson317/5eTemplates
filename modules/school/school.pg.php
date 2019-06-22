<?php
/******************************************************************************
 *
 * School List
 *
 ******************************************************************************/
function schoolList()
{
  $page = getUrlID('page', 1);
  $schools = getSchoolPager($page);

  $template = new ListPageTemplate('Schools');

  // Operations.
  $template->addOperation(a('New School', '/school'));

  if ($page > 1)
  {
    $attr = array(
      'query' => array('page' => ($page - 1)),
    );
    $template->addOperation(a('Prev Page', '/school', $attr));
  }

  if (count($schools) >= PAGER_SIZE_DEFAULT)
  {
    $attr = array(
      'query' => array('page' => ($page + 1)),
    );
    $template->addOperation(a('Next Page', '/school', $attr));
  }

  // List
  $table = new TableTemplate();
  $table->setAttr('class', array('school-list'));
  $table->setHeader(array('Name', 'Description'));

  foreach ($schools as $school)
  {
    $row = array();
    $attr = array(
      'query' => array('id' => $school['id'])
    );
    $row[] = a($school['name'], '/school', $attr);
    $row[] = $school['description'];
    $table->addRow($row);
  }
  $template->setList($table);
  return $template;
}

/******************************************************************************
 *
 * School Upsert
 *
 ******************************************************************************/
function schoolUpsertForm()
{
  $template = new FormPageTemplate();

  // Submit.
  if (isset($_SERVER['REQUEST_METHOD']) && ($_SERVER['REQUEST_METHOD'] == 'POST'))
  {
    $template->addMessage(schoolUpsertSubmit());
  }

  $school_id = getUrlID('id');

  $form = new Form('school_form');
  $title = 'Add New School';
  if ($school_id)
  {
    $school = getSchool($school_id);
    $form->setValues($school);
    $title = 'Edit school ' . htmlWrap('em', $school['name']);
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
  if ($school_id)
  {
    $value = 'Update';
  }
  $field = new FieldSubmit('submit', $value);
  $form->addField($field);

  // Delete.
  if ($school_id)
  {
    $field = new FieldSubmit('delete', 'Delete');
    $form->addField($field);
  }

  // Template.
  $template->setForm($form);
  return $template;
}

function schoolUpsertSubmit()
{
  $school = $_POST;
  unset($school['submit']);

  if (isset($_POST['delete']))
  {
    deleteSchool($school['id']);
    redirect('/schools');
  }

  // Update.
  if ($school['id'])
  {
    updateSchool($school);
    return htmlWrap('h3', 'School ' . htmlWrap('em', $school['name']) . ' (' . $school['id'] . ') updated.');
  }
  // Create.
  else
  {
    unset($school['id']);
    $school['id'] = createSchool($school);
    return htmlWrap('h3', 'School ' . htmlWrap('em', $school['name']) . ' (' . $school['id'] . ') created.');
  }
}
