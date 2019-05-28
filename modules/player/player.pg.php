<?php

/******************************************************************************
 *
 * Player List
 *
 ******************************************************************************/
function playerList()
{
  $page = getUrlID('page', 1);
  $players = getPlayerPager($page);

  $template = new ListPageTemplate('Players');

  // Operations.
  $template->addOperation(a('New Player', '/player'));

  if ($page > 1)
  {
    $attr = array(
      'query' => array('page' => ($page - 1)),
    );
    $template->addOperation(a('Prev Page', '/player', $attr));
  }

  if (count($players) >= DEFAULT_PAGER_SIZE)
  {
    $attr = array(
      'query' => array('page' => ($page + 1)),
    );
    $template->addOperation(a('Next Page', '/player', $attr));
  }

  // List
  $table = new TableTemplate();
  $table->setAttr('class', array('player-list'));
  $table->setHeader(array('Name'));

  foreach ($players as $player)
  {
    $row = array();
    $attr = array(
      'query' => array('id' => $player['id']),
    );
    $row[] = a($player['name'], '/player', $attr);
    $table->addRow($row);
  }
  $template->setList($table);
  return $template;
}

/******************************************************************************
 *
 * Player Upsert
 *
 ******************************************************************************/
function playerUpsertForm()
{
  $template = new FormPageTemplate();

  // Submit.
  if (isset($_SERVER['REQUEST_METHOD']) && ($_SERVER['REQUEST_METHOD'] == 'POST'))
  {
    $template->addMessage(playerUpsertSubmit());
  }

  $player_id = getUrlID('id');

  $form = new Form('player_form');
  $title = 'Add New Player';
  if ($player_id)
  {
    $player = getPlayer($player_id);
    $form->setValues($player);
    $title = 'Edit player ' . htmlWrap('em', $player['name']);
  }
  $form->setTitle($title);

  // ID.
  $field = new FieldHidden('id');
  $form->addField($field);

  // Name
  $field = new FieldText('name', 'Name');
  $form->addField($field);

  // Submit
  $value = 'Add';
  if ($player_id)
  {
    $value = 'Update';
  }
  $field = new FieldSubmit('submit', $value);
  $form->addField($field);

  // Delete.
  if ($player_id)
  {
    $field = new FieldSubmit('delete', 'Delete');
    $form->addField($field);
  }

  // Template.
  $template->setForm($form);
  return $template;
}

function playerUpsertSubmit()
{
  $player = $_POST;
  unset($player['submit']);

  if (isset($_POST['delete']))
  {
    deletePlayer($player['id']);
    redirect('/players');
  }

  // Update.
  if ($player['id'])
  {
    updatePlayer($player);
    return htmlWrap('h3', 'Player ' . htmlWrap('em', $player['name']) . ' (' . $player['id'] . ') updated.');
  }
  // Create.
  else
  {
    unset($player['id']);
    $player['id'] = createPlayer($player);
    return htmlWrap('h3', 'Player ' . htmlWrap('em', $player['name']) . ' (' . $player['id'] . ') created.');
  }
}

/******************************************************************************
 *
 * Source List
 *
 ******************************************************************************/
function sourceList()
{
  $page = getUrlID('page', 1);
  $sources = getSourcePager($page);

  $template = new ListPageTemplate('Sources');

  // Operations.
  $template->addOperation(a('New Source', '/source', $attr));

  if ($page > 1)
  {
    $attr = array(
      'query' => array('page' => ($page - 1)),
    );
    $template->addOperation(a('Prev Page', '/source', $attr));
  }

  if (count($sources) >= DEFAULT_PAGER_SIZE)
  {
    $attr = array(
      'query' => array('page' => ($page + 1)),
    );
    $template->addOperation(a('Next Page', '/player', $attr));
  }

  // List
  $table = new TableTemplate();
  $table->setAttr('class', array('source-list'));
  $table->setHeader(array('Name', 'Code'));

  foreach ($sources as $source)
  {
    $row = array();
    $attr = array(
      'query' => array('id' => $source['id']),
    );
    $row[] = a($source['name'], '/source', $attr);
    $row[] = $source['code'];
    $table->addRow($row);
  }
  $template->setList($table);
  return $template;
}

/******************************************************************************
 *
 * Source Upsert
 *
 ******************************************************************************/
function sourceUpsertForm()
{
  $template = new FormPageTemplate();

  // Submit.
  if (isset($_SERVER['REQUEST_METHOD']) && ($_SERVER['REQUEST_METHOD'] == 'POST'))
  {
    $template->addMessage(sourceUpsertSubmit());
  }

  $source_id = getUrlID('id');

  $form = new Form('source_form');
  $title = 'Add New source';
  if ($source_id)
  {
    $source = getSource($source_id);
    $form->setValues($source);
    $title = 'Edit source ' . htmlWrap('em', $source['name']);
  }
  $form->setTitle($title);

  // ID.
  $field = new FieldHidden('id');
  $form->addField($field);

  // Name
  $field = new FieldText('name', 'Name');
  $form->addField($field);

  // Name
  $field = new FieldText('code', 'Code');
  $form->addField($field);

  // Submit
  $value = 'Add';
  if ($source_id)
  {
    $value = 'Update';
  }
  $field = new FieldSubmit('submit', $value);
  $form->addField($field);

  // Delete.
  if ($source_id)
  {
    $field = new FieldSubmit('delete', 'Delete');
    $form->addField($field);
  }

  // Template.
  $template->setForm($form);
  return $template;
}

function sourceUpsertSubmit()
{
  $source = $_POST;
  unset($source['submit']);

  if (isset($_POST['delete']))
  {
    deleteSource($source['id']);
    redirect('/sources');
  }

  // Update.
  if ($_POST['id'])
  {
    updateSource($source);
    return htmlWrap('h3', 'Source ' . htmlWrap('em', $source['name']) . ' (' . $source['id'] . ') updated.');
  }
  // Create.
  else
  {
    unset($source['id']);
    $source['id'] = createSource($source);
    return htmlWrap('h3', 'Source ' . htmlWrap('em', $source['name']) . ' (' . $source['id'] . ') created.');
  }
}