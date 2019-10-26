<?php

function installDie()
{
  GLOBAL $db;

  // Touch, 5 ft, 60ft, etc.
  $query = new CreateQuery('dice');
  $query->addField('id', CreateQuery::TYPE_INTEGER, 0, array('P', 'U'));
  $query->addField('name', CreateQuery::TYPE_STRING, 32, array('N'));
  $query->addField('description', CreateQuery::TYPE_STRING, 1024);
  $db->create($query);

  $default_list = array(
    array(
      'id' => 2,
      'name' => 'd2',
      'description' => '',
    ),
    array(
      'id' => 4,
      'name' => 'd4',
      'description' => '',
    ),
    array(
      'id' => 6,
      'name' => 'd6',
      'description' => '',
    ),
    array(
      'id' => 8,
      'name' => 'd8',
      'description' => '',
    ),
    array(
      'id' => 10,
      'name' => 'd10',
      'description' => '',
    ),
    array(
      'id' => 12,
      'name' => 'd12',
      'description' => '',
    ),
    array(
      'id' => 20,
      'name' => 'd20',
      'description' => '',
    ),
    array(
      'id' => 100,
      'name' => 'd100',
      'description' => '',
    ),
  );

  foreach ($default_list as $die)
  {
    createDie($die);
  }
}

/**
 * @param int $page
 *
 * @return array|false
 */
function getDiePager($page = 1)
{
  GLOBAL $db;

  $query = new SelectQuery('dice');
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
function getDieList()
{
  GLOBAL $db;

  $query = new SelectQuery('dice');
  $query->addField('id')->addField('name', 'value');

  return $db->selectList($query);
}

/**
 * @param int $die_id
 *
 * @return array|false
 */
function getDie($die_id)
{
  GLOBAL $db;

  $query = new SelectQuery('dice');
  $query->addField('id');
  $query->addField('name');
  $query->addField('description');
  $query->addConditionSimple('id', $die_id);
  return $db->selectObject($query);
}

/**
 * @param array $die
 *
 * @return int
 */
function createDie($die)
{
  GLOBAL $db;

  $query = new InsertQuery('dice');
  $query->addField('id', $die['id']);
  $query->addField('name', $die['name']);
  $query->addField('description', $die['description']);

  return $db->insert($query);
}

/**
 * @param array $die
 */
function updateDie($die)
{
  GLOBAL $db;

  $query = new UpdateQuery('dice');
  $query->addField('name', $die['name']);
  $query->addField('description', $die['description']);
  $query->addConditionSimple('id', $die['id']);

  $db->update($query);
}

/**
 * @param int $die_id
 */
function deleteDie($die_id)
{
  GLOBAL $db;
  $query = new DeleteQuery('dice');
  $query->addConditionSimple('id', $die_id);

  $db->delete($query);
}
