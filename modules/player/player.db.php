<?php

/******************************************************************************
 *
 *  Install.
 *
 ******************************************************************************/
function installPlayer()
{
  GLOBAL $db;

  $query = new CreateQuery('players');
  $query->addField('id', 'INTEGER', array('P', 'A'));
  $query->addField('name', 'TEXT', array('N'));
  $db->create($query);

  $query = new CreateQuery('sources');
  $query->addField('id', 'INTEGER', array('P', 'A'));
  $query->addField('code', 'TEXT', array('N'));
  $query->addField('name', 'TEXT', array('N'));
  $db->create($query);
}

/******************************************************************************
 *
 *  Player.
 *
 ******************************************************************************/

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

/******************************************************************************
 *
 *  Sources.
 *
 ******************************************************************************/

/**
 * @param int $page
 *
 * @return array|false
 */
function getSourcePager($page = 1)
{
  GLOBAL $db;

  $query = new SelectQuery('sources');
  $query->addField('id');
  $query->addField('name');
  $query->addField('code');
  $query->addPager($page);

  return $db->select($query);
}

/**
 * @return array
 */
function getSourceList()
{
  GLOBAL $db;

  $query = new SelectQuery('sources');
  $query->addField('id')->addField($db->concatenate('code', $db->literal(' - '), 'name'), 'value');

  return $db->selectList($query);
}

/**
 * @param $source_id
 *
 * @return array|FALSE
 */
function getSource($source_id)
{
  GLOBAL $db;

  $query = new SelectQuery('sources');
  $query->addField('id');
  $query->addField('name');
  $query->addField('code');
  $query->addConditionSimple('id', $source_id);

  return $db->selectObject($query);
}

/**
 * @param array $source
 *
 * @return int
 */
function createSource($source)
{
  GLOBAL $db;

  $query = new InsertQuery('sources');
  $query->addField('name', $source['name']);
  $query->addField('code', $source['code']);

  return $db->insert($query);
}

/**
 * @param $source
 */
function updateSource($source)
{
  GLOBAL $db;

  $query = new UpdateQuery('sources');
  $query->addField('name', $source['name']);
  $query->addField('code', $source['code']);
  $query->addConditionSimple('id', $source['id']);

  $db->update($query);
}

/**
 * @param int $source_id
 */
function deleteSource($source_id)
{
  GLOBAL $db;

  $query = new DeleteQuery('sources');
  $query->addConditionSimple('id', $source_id);

  $db->delete($query);
}
