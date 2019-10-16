<?php

function subraceAjax()
{
  $race_id = getUrlID('race_id');

  $list = getSubraceList($race_id);

  die(optionList($list));
}

function subraceUpsertForm()
{
  $template = new FormPageTemplate();

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
      'query' => array('id' => $subrace['id']),
    );
    $row[] = a($subrace['name'], '/subrace', $attr);
    $row[] = $subrace['description'];
    $table->addRow($row);
  }

  $link = '';
  if ($subrace_id)
  {
    $attr = array(
      'query' => array('race_id' => $race['id']),
    );
    $link = a('Add New Subrace', '/subrace', $attr);
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
  $options = array(0 => '--Select One--') + getSourceDetailList();
  $field = new FieldSelect('source_id', 'Source', $options);
  $form->addField($field);

  // Back link.
  $attr = array(
    'query' => array('id' => $race['id']),
  );
  $link = a('Back to race ' . $race['name'], '/race', $attr);

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
    $attr = array('query' => array('id' => $subrace['race_id']));
    redirect('/race', 303, $attr);
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

