<?php

function installPlayer()
{
  GLOBAL $db;

  $query = new CreateQuery('players');
  $query->addField('id', 'INTEGER', 0, array('P', 'A'));
  $query->addField('name', 'TEXT', 32, array('N'));
  $db->create($query);

  $player = array('name' => 'Daniel P. Henry');
  createPlayer($player);
}

/**
 * @param int $page
 *
 * @return array|false
 */
function getPlayerPager($page = 1)
{
  GLOBAL $db;

  $query = new SelectQuery('players');
  $query->addField('id');
  $query->addField('name');
  $query->addPager($page);

  return $db->select($query);
}

/**
 * @return array
 */
function getPlayerList()
{
  GLOBAL $db;

  $query = new SelectQuery('players');
  $query->addField('id')->addField('name', 'value');

  return $db->selectList($query);
}

/**
 * @param $player_id
 *
 * @return array|false
 */
function getPlayer($player_id)
{
  GLOBAL $db;

  $query = new SelectQuery('players');
  $query->addField('id');
  $query->addField('name');
  $query->addConditionSimple('id', $player_id);

  return $db->selectObject($query);
}

/**
 * @param array $player
 *
 * @return int
 */
function createPlayer($player)
{
  GLOBAL $db;

  $query = new InsertQuery('players');
  $query->addField('name', $player['name']);

  return $db->insert($query);
}

/**
 * @param array $player
 */
function updatePlayer($player)
{
  GLOBAL $db;

  $query = new UpdateQuery('players');
  $query->addField('name', $player['name']);
  $query->addConditionSimple('id', $player['id']);

  $db->update($query);
}

/**
 * @param int $player_id
 */
function deletePlayer($player_id)
{
  GLOBAL $db;

  $query = new DeleteQuery('players');
  $query->addConditionSimple('id', $player_id);

  $db->delete($query);
}
