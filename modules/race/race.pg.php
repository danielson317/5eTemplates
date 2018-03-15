<?php

/******************************************************************************
 *
 *  Race List
 *
 ******************************************************************************/
function raceList()
{
  $page = getUrlID('page', 1);
  $races = getRacePager($page);

  $template = new ListTemplate('Race');

  // Operations.
  $attr = array(
    'href' => 'race',
  );
  $template->addOperation(htmlWrap('a', 'New Race', $attr));

  if ($page > 1)
  {
    $attr = array(
      'href' => '?page=' . ($page - 1),
    );
    $template->addOperation(htmlWrap('a', 'Prev Page', $attr));
  }

  if (count($races) >= DEFAULT_PAGER_SIZE)
  {
    $attr = array(
      'href' => '?page=' . ($page + 1),
    );
    $template->addOperation(htmlWrap('a', 'Next Page', $attr));
  }

  // List
  $table = new TableTemplate();
  $table->setAttr('race', array('race-list'));
  $table->setHeader(array('Name', 'Description'));

  foreach ($races as $race)
  {
    $row = array();
    $attr = array(
      'href' => '/race?id=' . $race['id'],
    );
    $row[] = htmlWrap('a', $race['name'], $attr);
    $row[] = $race['description'];
    $table->addRow($row);
  }
  $template->setList($table);
  return $template;
}

/******************************************************************************
 *
 * Race Upsert
 *
 ******************************************************************************/
function raceUpsertForm()
{
  $template = new FormTemplate();

  // Submit.
  if (isset($_SERVER['REQUEST_METHOD']) && ($_SERVER['REQUEST_METHOD'] == 'POST'))
  {
    $template->addMessage(raceUpsertSubmit());
  }

  $race_id = getUrlID('id');

  $form = new Form('race_form');
  $title = 'Add new race';
  if ($race_id)
  {
    $race = getRace($race_id);
    $form->setValues($race);
    $title = 'Edit race ' . htmlWrap('em', $race['name']);
  }
  $form->setTitle($title);

  // ID.
  $field = new FieldHidden('id');
  $form->addField($field);

  // Name
  $field = new FieldText('name', 'Name');
  $form->addField($field);

  // Speed.
  $field = new FieldNumber('speed', 'Base Movement Speed');
  $form->addField($field);

  // Description.
  $field = new FieldTextarea('description', 'Description');
  $form->addField($field);

  // Subraces
  if ($race_id)
  {
    $subraces = getSubracePager($race_id);

    $table = new TableTemplate('subraces');
    $table->setHeader(array('Subrace', 'Description'));
    foreach ($subraces as $subrace)
    {
      $row = array();
      $attr = array(
        'href' => '/subrace?id=' . $subrace['id'],
      );
      $row[] = htmlWrap('a', $subrace['name'], $attr);
      $row[] = $subrace['description'];
      $table->addRow($row);
    }

    $attr = array(
      'href' => '/subrace?race_id=' . $race['id'],
    );
    $link = htmlWrap('a', 'Add New Subrace', $attr);

    $field = new FieldMarkup('subraces', '', $table . $link);
    $form->addField($field);
  }

  // Source.
  $options = array(0 => '--Select One--') + getSourceList();
  $field = new FieldSelect('source_id', 'Source', $options);
  $form->addField($field);

  // Submit
  $value = 'Add';
  if ($race_id)
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

function raceUpsertSubmit()
{
  if (isset($_POST['delete']))
  {
    deleteRace($_POST['id']);
    redirect('/races');
  }

  $race = $_POST;
  unset($race['submit']);

  // Update.
  if ($_POST['id'])
  {
    updateRace($race);
    return htmlWrap('h3', 'Race ' . htmlWrap('em', $race['name']) . ' (' . $race['id'] . ') updated.');
  }
  // Create.
  else
  {
    unset($race['id']);
    $race['id'] = createRace($race);
    return htmlWrap('h3', 'Race ' . htmlWrap('em', $race['name']) . ' (' . $race['id'] . ') created.');
  }
}

/******************************************************************************
 *
 * Subrace List
 *
 ******************************************************************************/
function subraceAjax()
{
  $race_id = getUrlID('race_id');

  $list = array(0 => '--Select One--') + getSubraceList($race_id);

  die(optionList($list));
}

/******************************************************************************
 *
 * Subrace Upsert
 *
 ******************************************************************************/
function subraceUpsertForm()
{
  $template = new FormTemplate();

  // Submit.
  if (isset($_SERVER['REQUEST_METHOD']) && ($_SERVER['REQUEST_METHOD'] == 'POST'))
  {
    $template->addMessage(subraceUpsertSubmit());
  }

  $subrace_id = getUrlID('id');

  $form = new Form('subrace_form');
  if ($subrace_id)
  {
    $subrace = getSubrace($subrace_id);
    $race_id = $subrace['race_id'];
    $race = getRace($subrace['race_id']);
    $form->setValues($subrace);
    $title = 'Edit subrace ' . htmlWrap('em', $subrace['name']) . ' of race ' . htmlWrap('em', $race['name']);
  }
  else
  {
    $race_id = getUrlID('race_id');
    $race = getRace($race_id);
    $title = 'Add new subrace to race ' . htmlWrap('em', $race['name']);
  }
  $form->setTitle($title);

  // ID.
  $field = new FieldHidden('id');
  $form->addField($field);

  // Race ID.
  $field = new FieldHidden('race_id');
  $field->setValue($race['id']);
  $form->addField($field);

  // Sub races.
  $subraces = getSubracePager($race_id);
  $table = new TableTemplate('subraces');
  $table->setHeader(array('Subrace', 'Description'));
  foreach ($subraces as $subrace)
  {
    $row = array();
    $attr = array(
      'href' => '/subrace?id=' . $subrace['id'],
    );
    $row[] = htmlWrap('a', $subrace['name'], $attr);
    $row[] = $subrace['description'];
    $table->addRow($row);
  }

  $link = '';
  if ($subrace_id)
  {
    $attr = array(
      'href' => '/subrace?race_id=' . $race['id'],
    );
    $link = htmlWrap('a', 'Add New Subrace', $attr);
  }

  $field = new FieldMarkup('subraces', '', $table . $link);
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
    'href' => '/race?id=' . $race['id'],
  );
  $link = htmlWrap('a', 'Back to race ' . $race['name'], $attr);

  $field = new FieldMarkup('links', '', $link);
  $form->addField($field);

  // Submit
  $value = 'Add';
  if ($subrace_id)
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

function subraceUpsertSubmit()
{
  $subrace = $_POST;
  unset($subrace['submit']);

  // Delete.
  if (isset($_POST['delete']))
  {
    deleteSubrace($subrace['id']);
    redirect('/race?id=' . $subrace['race_id']);
  }

  // Update.
  if ($_POST['id'])
  {
    unset($subrace['race_id']);
    updateSubrace($subrace);
    return htmlWrap('h3', 'Sublass ' . htmlWrap('em', $subrace['name']) . ' (' . $subrace['id'] . ') updated.');
  }
  // Create.
  else
  {
    unset($subrace['id']);
    $subrace['id'] = createSubrace($subrace);
    return htmlWrap('h3', 'Subrace ' . htmlWrap('em', $subrace['name']) . ' (' . $subrace['id'] . ') created.');
  }
}
