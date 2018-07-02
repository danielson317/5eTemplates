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
function getPlayerPager($page = 1)
{
  GLOBAL $db;

  $query = new SelectQuery('players');
  $query->addField('id');
  $query->addField('name');
  $query->addPager($page);

  $results = $db->select($query);

  if (!$results)
  {
    return array();
  }
  return $results;
}

function getPlayerList()
{
  GLOBAL $db;

  $query = new SelectQuery('players');
  $query->addField('id')->addField('name', 'value');

  return $db->selectList($query);
}

function getPlayer($id)
{
  GLOBAL $db;

  $query = new SelectQuery('players');
  $query->addField('id');
  $query->addField('name');
  $query->addConditionSimple('id', $id);

  $results = $db->select($query);
  if (!$results)
  {
    return FALSE;
  }
  $result = array_shift($results);
  return $result;
}

function createPlayer($player)
{
  GLOBAL $db;

  $query = new InsertQuery('players');
  $query->addField('name');
  $args = $db->buildArgs($player);

  return $db->insert($query, $args);
}

function updatePlayer($player)
{
  GLOBAL $db;

  $query = new UpdateQuery('players');
  $query->addField('name');
  $query->addConditionSimple('id', $player['id']);

  $db->update($query);
}

function deletePlayer($id)
{
  GLOBAL $db;

  $query = new DeleteQuery('players');
  $query->addConditionSimple('id', $id);

  $db->delete($query);
}

/******************************************************************************
 *
 *  Sources.
 *
 ******************************************************************************/
function getSourcePager($page = 1)
{
  GLOBAL $db;

  $query = new SelectQuery('sources');
  $query->addField('id');
  $query->addField('name');
  $query->addField('code');
  $query->addPager($page);

  $results = $db->select($query);

  if (!$results)
  {
    return array();
  }
  return $results;
}

function getSourceList()
{
  GLOBAL $db;

  $query = new SelectQuery('sources');
  $query->addField('id')->addField($db->concatenate('code', $db->literal(' - '), 'name'), 'value');

  return $db->selectList($query);
}

function getSource($id)
{
  GLOBAL $db;

  $query = new SelectQuery('sources');
  $query->addField('id');
  $query->addField('name');
  $query->addField('code');
  $query->addConditionSimple('id', $id);

  $results = $db->select($query);
  if (!$results)
  {
    return FALSE;
  }
  $result = array_shift($results);
  return $result;
}

function createSource($source)
{
  GLOBAL $db;

  $query = new InsertQuery('sources');
  $query->addField('name');
  $query->addField('code');
  $args = $db->buildArgs($source);

  return $db->insert($query, $args);
}

function updateSource($source)
{
  GLOBAL $db;

  $query = new UpdateQuery('sources');
  $query->addField('name');
  $query->addField('code');
  $query->addConditionSimple('id', $source['id']);

  $db->update($query);
}

function deleteSource($id)
{
  GLOBAL $db;

  $query = new DeleteQuery('sources');
  $query->addConditionSimple('id', $id);

  $db->delete($query);
}
