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

  $template = new ListPageTemplate('Race');

  // Operations.
  $template->addOperation(a('New Race', '/race', $attr));

  if ($page > 1)
  {
    $attr = array(
      'query' => array('page' => ($page - 1)),
    );
    $template->addOperation(a('Prev Page', '/race', $attr));
  }

  if (count($races) >= DEFAULT_PAGER_SIZE)
  {
    $attr = array(
      'query' => array('page' => ($page + 1)),
    );
    $template->addOperation(a('next Page', '/race', $attr));
  }

  // List
  $table = new TableTemplate();
  $table->setAttr('race', array('race-list'));
  $table->setHeader(array('Name', 'Description'));

  foreach ($races as $race)
  {
    $row = array();
    $attr = array(
      'query' => array('id' => $race['id']),
    );
    $row[] = a($race['name'], '/race', $attr);
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
  $template = new FormPageTemplate();

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
        'query' => array('id' => $subrace['id']),
      );
      $row[] = a($subrace['name'], '/subrace', $attr);
      $row[] = $subrace['description'];
      $table->addRow($row);
    }

    $attr = array(
      'query' => array('race_id' => $race['id']),
    );
    $link = a('Add New Subrace', '/subrace', $attr);

    $field = new FieldMarkup('subraces', '', $table . $link);
    $form->addField($field);
  }

  // Source.
  $options = array(0 => '--Select One--') + getSourceDetailList();
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
