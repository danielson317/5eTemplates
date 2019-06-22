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

  if (count($players) >= PAGER_SIZE_DEFAULT)
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
