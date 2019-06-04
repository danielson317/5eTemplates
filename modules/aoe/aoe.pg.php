<?php
/******************************************************************************
 *
 * Aoe List
 *
 ******************************************************************************/
function aoeList()
{
  $page = getUrlID('page', 1);
  $aoes = getAoePager($page);

  $template = new ListPageTemplate('Aoes');

  // Operations.
  $template->addOperation(a('New Aoe', '/aoe'));

  if ($page > 1)
  {
    $attr = array(
      'query' => array('page' => ($page - 1)),
    );
    $template->addOperation(a('Prev Page', '/aoe', $attr));
  }

  if (count($aoes) >= DEFAULT_PAGER_SIZE)
  {
    $attr = array(
      'query' => array('page' => ($page + 1)),
    );
    $template->addOperation(a('Next Page', '/aoe', $attr));
  }

  // List
  $table = new TableTemplate();
  $table->setAttr('class', array('aoe-list'));
  $table->setHeader(array('Name', 'Description'));

  foreach ($aoes as $aoe)
  {
    $row = array();
    $attr = array(
      'query' => array('id' => $aoe['id'])
    );
    $row[] = a($aoe['name'], '/aoe', $attr);
    $row[] = $aoe['description'];
    $table->addRow($row);
  }
  $template->setList($table);
  return $template;
}

/******************************************************************************
 *
 * Aoe Upsert
 *
 ******************************************************************************/
function aoeUpsertForm()
{
  $template = new FormPageTemplate();

  // Submit.
  if (isset($_SERVER['REQUEST_METHOD']) && ($_SERVER['REQUEST_METHOD'] == 'POST'))
  {
    $template->addMessage(aoeUpsertSubmit());
  }

  $aoe_id = getUrlID('id');

  $form = new Form('aoe_form');
  $title = 'Add New Aoe';
  if ($aoe_id)
  {
    $aoe = getAoe($aoe_id);
    $form->setValues($aoe);
    $title = 'Edit aoe ' . htmlWrap('em', $aoe['name']);
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
  if ($aoe_id)
  {
    $value = 'Update';
  }
  $field = new FieldSubmit('submit', $value);
  $form->addField($field);

  // Delete.
  if ($aoe_id)
  {
    $field = new FieldSubmit('delete', 'Delete');
    $form->addField($field);
  }

  // Template.
  $template->setForm($form);
  return $template;
}

function aoeUpsertSubmit()
{
  $aoe = $_POST;
  unset($aoe['submit']);

  if (isset($_POST['delete']))
  {
    deleteAoe($aoe['id']);
    redirect('/aoes');
  }

  // Update.
  if ($aoe['id'])
  {
    updateAoe($aoe);
    return htmlWrap('h3', 'Aoe ' . htmlWrap('em', $aoe['name']) . ' (' . $aoe['id'] . ') updated.');
  }
  // Create.
  else
  {
    unset($aoe['id']);
    $aoe['id'] = createAoe($aoe);
    return htmlWrap('h3', 'Aoe ' . htmlWrap('em', $aoe['name']) . ' (' . $aoe['id'] . ') created.');
  }
}
