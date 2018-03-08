<?php

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
