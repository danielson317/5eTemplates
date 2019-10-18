<?php

function installRange()
{
  GLOBAL $db;

  // Touch, 5 ft, 60ft, etc.
  $query = new CreateQuery('ranges');
  $query->addField('id', CreateQuery::TYPE_INTEGER, 0, array('P', 'U'));
  $query->addField('name', CreateQuery::TYPE_STRING, 32, array('N'));
  $query->addField('description', CreateQuery::TYPE_STRING, 1024);
  $db->create($query);

  $ranges = array(
    array(
      'id' => 1,
      'name' => 'Self',
      'description' => '',
    ),
    array(
      'id' => 2,
      'name' => 'Unlimited',
      'description' => '',
    ),
    array(
      'id' => 3,
      'name' => 'Same Plane',
      'description' => '',
    ),
    array(
      'id' => 4,
      'name' => 'Touch',
      'description' => '',
    ),
    array(
      'id' => 5,
      'name' => '5 Feet',
      'description' => '',
    ),
    array(
      'id' => 6,
      'name' => 'Sight',
      'description' => '',
    ),
    array(
      'id' => 7,
      'name' => 'Reach',
      'description' => '',
    ),
    array(
      'id' => 10,
      'name' => '10 Feet',
      'description' => '',
    ),
    array(
      'id' => 15,
      'name' => '15 Feet',
      'description' => '',
    ),
    array(
      'id' => 30,
      'name' => '30 Feet',
      'description' => '',
    ),
    array(
      'id' => 60,
      'name' => '60 Feet',
      'description' => '',
    ),
    array(
      'id' => 90,
      'name' => '90 Feet',
      'description' => '',
    ),
    array(
      'id' => 100,
      'name' => '100 Feet',
      'description' => '',
    ),
    array(
      'id' => 120,
      'name' => '120 Feet',
      'description' => '',
    ),
    array(
      'id' => 150,
      'name' => '150 Feet',
      'description' => '',
    ),
    array(
      'id' => 300,
      'name' => '300 Feet',
      'description' => '',
    ),
    array(
      'id' => 500,
      'name' => '10 Feet',
      'description' => '',
    ),
    array(
      'id' => 5280,
      'name' => '1 Mile',
      'description' => '',
    ),
    array(
      'id' => 26400,
      'name' => '5 Miles',
      'description' => '',
    ),
  );
  
  foreach ($ranges as $range)
  {
    createRange($range);
  }
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
