<?php

function installRange()
{
  GLOBAL $db;

  // Touch, 5 ft, 60ft, etc.
  $query = new CreateQuery('ranges');
  $query->addField('id', 'INTEGER', 0, array('P', 'U'));
  $query->addField('name', 'TEXT', 32, array('N'));
  $query->addField('description', 'TEXT', 1024);
  $db->create($query);

}

/**
 * @param int $page
 *
 * @return array|false
 */
function getRangePager($page = 1)
{
  GLOBAL $db;

  $query = new SelectQuery('ranges');
  $query->addField('id');
  $query->addField('name');
  $query->addField('description');
  $query->addOrderSimple('id');
  $query->addPager($page);

  return $db->select($query);
}

/**
 * @return array
 */
function getRangeList()
{
  GLOBAL $db;

  $query = new SelectQuery('ranges');
  $query->addField('id')->addField('name', 'value');

  return $db->selectList($query);
}

/**
 * @param int $range_id
 *
 * @return array|false
 */
function getRange($range_id)
{
  GLOBAL $db;

  $query = new SelectQuery('ranges');
  $query->addField('id');
  $query->addField('name');
  $query->addField('description');
  $query->addConditionSimple('id', $range_id);
  return $db->selectObject($query);
}

/**
 * @param array $range
 *
 * @return int
 */
function createRange($range)
{
  GLOBAL $db;

  $query = new InsertQuery('ranges');
  $query->addField('id', $range['id']);
  $query->addField('name', $range['name']);
  $query->addField('description', $range['description']);

  return $db->insert($query);
}

/**
 * @param array $range
 */
function updateRange($range)
{
  GLOBAL $db;

  $query = new UpdateQuery('ranges');
  $query->addField('name', $range['name']);
  $query->addField('description', $range['description']);
  $query->addConditionSimple('id', $range['id']);

  $db->update($query);
}

/**
 * @param int $range_id
 */
function deleteRange($range_id)
{
  GLOBAL $db;
  $query = new DeleteQuery('ranges');
  $query->addConditionSimple('id', $range_id);

  $db->delete($query);
}
