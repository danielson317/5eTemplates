<?php

/******************************************************************************
 *
 * List
 *
 ******************************************************************************/
function playerList()
{
  $page = getUrlID('page', 1);
  $players = getPlayerPager($page);

  $template = new ListTemplate('Players');

  // Operations.
  $attr = array(
    'href' => 'player',
  );
  $template->addOperation(htmlWrap('a', 'New Player', $attr));

  if ($page > 1)
  {
    $attr = array(
      'href' => '?page=' . ($page - 1),
    );
    $template->addOperation(htmlWrap('a', 'Prev Page', $attr));
  }

  if (count($players) >= DEFAULT_PAGER_SIZE)
  {
    $attr = array(
      'href' => '?page=' . ($page + 1),
    );
    $template->addOperation(htmlWrap('a', 'Next Page', $attr));
  }

  // List
  $table = new TableTemplate();
  $table->setAttr('class', array('player-list'));
  $table->setHeader(array('Name'));

  foreach ($players as $player)
  {
    $row = array();
    $attr = array(
      'href' => '/player?id=' . $player['id'],
    );
    $row[] = htmlWrap('a', $player['name'], $attr);
    $table->addRow($row);
  }
  $template->setList($table);
  return $template;
}

/******************************************************************************
 *
 * Upsert
 *
 ******************************************************************************/
function PlayerUpsertForm()
{
  $template = new FormTemplate();

  // Submit.
  if (isset($_SERVER['REQUEST_METHOD']) && ($_SERVER['REQUEST_METHOD'] == 'POST'))
  {
    $template->addMessage(playerUpsertSubmit());
  }

  $player_id = getUrlID('id');

  $form = new Form('character_form');
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
  $field = new FieldSubmit('delete', 'Delete');
  $form->addField($field);

  // Template.
  $template->setForm($form);
  return $template;
}

function playerUpsertSubmit()
{
  if (isset($_POST['delete']))
  {
    deletePlayer($_POST['id']);
    redirect('/players');
  }

  // Update.
  if ($_POST['id'])
  {
    $player = array(
      'id' => $_POST['id'],
      'name' => $_POST['name'],
    );
    updatePlayer($player);
    return htmlWrap('h3', 'Player ' . htmlWrap('em', $player['name']) . ' (' . $player['id'] . ') updated.');
  }
  // Create.
  else
  {
    $player = array(
      'name' => $_POST['name'],
    );
    $player['id'] = createPlayer($player);
    return htmlWrap('h3', 'Player ' . htmlWrap('em', $player['name']) . ' (' . $player['id'] . ') created.');
  }
}
