<?php

function installAoe()
{
  GLOBAL $db;

  // Line, cone, cube, etc.
  $query = new CreateQuery('aoes');
  $query->addField('id', 'INTEGER', 0, array('P', 'A'));
  $query->addField('name', 'TEXT', 32, array('N'));
  $query->addField('description', 'TEXT', 1024);
  $db->create($query);
}

/**
 * @param int $page
 *
 * @return array|false
 */
function getAoePager($page = 1)
{
  GLOBAL $db;

  $query = new SelectQuery('aoes');
  $query->addField('id');
  $query->addField('name');
  $query->addField('description');
  $query->addPager($page);

  return $db->select($query);
}

/**
 * @return array
 */
function getAoeList()
{
  GLOBAL $db;

  $query = new SelectQuery('aoes');
  $query->addField('id')->addField('name', 'value');

  return $db->selectList($query);
}

/**
 * @param int $aoe_id
 *
 * @return array|false
 */
function getAoe($aoe_id)
{
  GLOBAL $db;

  $query = new SelectQuery('aoes');
  $query->addField('id');
  $query->addField('name');
  $query->addField('description');
  $query->addConditionSimple('id', $aoe_id);

  return $db->selectObject($query);
}

/**
 * @param array $aoe
 *
 * @return int
 */
function createAoe($aoe)
{
  GLOBAL $db;

  $query = new InsertQuery('aoes');
  $query->addField('name', $aoe['name']);
  $query->addField('description', $aoe['description']);

  return $db->insert($query);
}

/**
 * @param array $aoe
 */
function updateAoe($aoe)
{
  GLOBAL $db;

  $query = new updateQuery('aoes');
  $query->addField('name', $aoe['name']);
  $query->addField('description', $aoe['description']);
  $query->addConditionSimple('id', $aoe['id']);

  $db->update($query);
}

/**
 * @param int $aoe_id
 */
function deleteAoe($aoe_id)
{
  GLOBAL $db;

  $query = new DeleteQuery('aoes');
  $query->addConditionSimple('id', $aoe_id);

  $db->delete($query);
}
