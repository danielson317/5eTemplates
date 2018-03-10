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
  $query->addField('id')->addField('name')->addPager($page);

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
  $query->addCondition('id');
  $args = array(':id' => $id);

  $results = $db->select($query, $args);
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
  $query->addCondition('id');
  $args = $db->buildArgs($player);

  $db->update($query, $args);
}

function deletePlayer($id)
{
  GLOBAL $db;

  $query = new DeleteQuery('players');
  $query->addCondition('id');
  $args = array(':id' => $id);

  $db->delete($query, $args);
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
  $query->addField('id')->addField($query::concatenate('code', $query::literal(' - '), 'name'), 'value');

  return $db->selectList($query);
}

function getSource($id)
{
  GLOBAL $db;

  $query = new SelectQuery('sources');
  $query->addField('id');
  $query->addField('name');
  $query->addField('code');
  $query->addCondition('id');
  $args = array(':id' => $id);

  $results = $db->select($query, $args);
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
  $query->addCondition('id');
  $args = $db->buildArgs($source);

  $db->update($query, $args);
}

function deleteSource($id)
{
  GLOBAL $db;

  $query = new DeleteQuery('sources');
  $query->addCondition('id');
  $args = array(':id' => $id);

  $db->delete($query, $args);
}
