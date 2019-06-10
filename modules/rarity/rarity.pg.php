<?php
/******************************************************************************
 *
 * Rarity List
 *
 ******************************************************************************/
function rarityList()
{
  $page = getUrlID('page', 1);
  $rarities = getRarityPager($page);

  $template = new ListPageTemplate('Rarities');

  // Operations.
  $template->addOperation(a('New Rarity', '/rarity'));

  if ($page > 1)
  {
    $attr = array(
      'query' => array('page' => ($page - 1)),
    );
    $template->addOperation(a('Prev Page', '/rarity', $attr));
  }

  if (count($rarities) >= DEFAULT_PAGER_SIZE)
  {
    $attr = array(
      'query' => array('page' => ($page + 1)),
    );
    $template->addOperation(a('Next Page', '/rarity', $attr));
  }

  // List
  $table = new TableTemplate();
  $table->setAttr('class', array('rarity-list'));
  $table->setHeader(array('Name', 'Description'));

  foreach ($rarities as $rarity)
  {
    $row = array();
    $attr = array(
      'query' => array('id' => $rarity['id'])
    );
    $row[] = a($rarity['name'], '/rarity', $attr);
    $row[] = $rarity['description'];
    $table->addRow($row);
  }
  $template->setList($table);
  return $template;
}

/******************************************************************************
 *
 * Rarity Upsert
 *
 ******************************************************************************/
function rarityUpsertForm()
{
  $template = new FormPageTemplate();

  // Submit.
  if (isset($_SERVER['REQUEST_METHOD']) && ($_SERVER['REQUEST_METHOD'] == 'POST'))
  {
    $template->addMessage(rarityUpsertSubmit());
  }

  $rarity_id = getUrlID('id');

  $form = new Form('rarity_form');
  $title = 'Add New Rarity';
  if ($rarity_id)
  {
    $rarity = getRarity($rarity_id);
    $form->setValues($rarity);
    $title = 'Edit rarity ' . htmlWrap('em', $rarity['name']);
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
  if ($rarity_id)
  {
    $value = 'Update';
  }
  $field = new FieldSubmit('submit', $value);
  $form->addField($field);

  // Delete.
  if ($rarity_id)
  {
    $field = new FieldSubmit('delete', 'Delete');
    $form->addField($field);
  }

  // Template.
  $template->setForm($form);
  return $template;
}

function rarityUpsertSubmit()
{
  $rarity = $_POST;
  unset($rarity['submit']);

  if (isset($_POST['delete']))
  {
    deleteRarity($rarity['id']);
    redirect('/rarities');
  }

  // Update.
  if ($rarity['id'])
  {
    updateRarity($rarity);
    return htmlWrap('h3', 'Rarity ' . htmlWrap('em', $rarity['name']) . ' (' . $rarity['id'] . ') updated.');
  }
  // Create.
  else
  {
    unset($rarity['id']);
    $rarity['id'] = createRarity($rarity);
    return htmlWrap('h3', 'Rarity ' . htmlWrap('em', $rarity['name']) . ' (' . $rarity['id'] . ') created.');
  }
}
