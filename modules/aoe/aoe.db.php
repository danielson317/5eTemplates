<?php

function installAoe()
{
  GLOBAL $db;

  // Line, cone, cube, etc.
  $query = new CreateQuery('aoes');
  $query->addField('id', CreateQuery::TYPE_INTEGER, 0, array('P', 'A'));
  $query->addField('name', CreateQuery::TYPE_STRING, 32, array('N'));
  $query->addField('description', CreateQuery::TYPE_STRING, 1024);
  $db->create($query);

  $aoes = array(
    array(
      'name' => 'Cone',
      'description' => '',
    ),
    array(
      'name' => 'Cube',
      'description' => '',
    ),
    array(
      'name' => 'Cylinders',
      'description' => '',
    ),
    array(
      'name' => 'Line',
      'description' => '',
    ),
    array(
      'name' => 'Sphere',
      'description' => '',
    ),
  );

  foreach ($aoes as $aoe)
  {
    createAoe($aoe);
  }
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
