<?php

function installSpeed()
{
  GLOBAL $db;

  // Action, Reaction, 1 minute, etc.
  $query = new CreateQuery('speeds');
  $query->addField('id', 'INTEGER', 0, array('P', 'U'));
  $query->addField('casting_time', 'TEXT', 32, array('N'));
  $query->addField('duration', 'TEXT', 32, array('N'));
  $query->addField('description', 'TEXT', 1024);
  $db->create($query);
}

/**
 * @param int $page
 *
 * @return array|false
 */
function getSpeedPager($page = 1)
{
  GLOBAL $db;

  $query = new SelectQuery('speeds');
  $query->addField('id');
  $query->addField('casting_time');
  $query->addField('duration');
  $query->addField('description');
  $query->addPager($page);

  return $db->select($query);
}

function getSpeedCastingTimeList()
{
  GLOBAL $db;

  $query = new SelectQuery('speeds');
  $query->addField('id')->addField('casting_time', 'value');
  return $db->selectList($query);
}

function getSpeedDurationList()
{
  GLOBAL $db;

  $query = new SelectQuery('speeds');
  $query->addField('id')->addField('duration', 'value');
  $query->addConditionSimple('duration', '', QueryCondition::COMPARE_NOT_EQUAL);

  return $db->selectList($query);
}

/**
 * @param int $speed_id
 *
 * @return array|false
 */
function getSpeed($speed_id)
{
  GLOBAL $db;

  $query = new SelectQuery('speeds');
  $query->addField('id');
  $query->addField('casting_time');
  $query->addField('duration');
  $query->addField('description');
  $query->addConditionSimple('id', $speed_id);

  return $db->selectObject($query);
}

/**
 * @param array $speed
 *
 * @return int
 */
function createSpeed($speed)
{
  GLOBAL $db;

  $query = new InsertQuery('speeds');
  $query->addField('id', $speed['id']);
  $query->addField('casting_time', $speed['casting_time']);
  $query->addField('duration', $speed['duration']);
  $query->addField('description', $speed['description']);

  return $db->insert($query);
}

/**
 * @param array $speed
 */
function updateSpeed($speed)
{
  GLOBAL $db;

  $query = new updateQuery('speeds');
  $query->addField('casting_time', $speed['casting_time']);
  $query->addField('duration', $speed['duration']);
  $query->addField('description', $speed['description']);
  $query->addConditionSimple('id', $speed['id']);

  $db->update($query);
}

/**
 * @param int $speed_id
 */
function deleteSpeed($speed_id)
{
  GLOBAL $db;

  $query = new DeleteQuery('speeds');
  $query->addConditionSimple('id', $speed_id);

  $db->delete($query);
}
