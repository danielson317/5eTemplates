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

  $speeds = array(
    array(
      'id' => 1,
      'casting_time' => 'Instant',
      'duration' => 'Forever',
      'description' => '',
    ),
    array(
      'id' => 2,
      'casting_time' => 'Reaction',
      'duration' => '',
      'description' => '',
    ),
    array(
      'id' => 3,
      'casting_time' => 'BA',
      'duration' => 'End of targets next turn',
      'description' => '',
    ),
    array(
      'id' => 6,
      'casting_time' => 'Action',
      'duration' => 'End of your next turn',
      'description' => '',
    ),
    array(
      'id' => 60,
      'casting_time' => '1 Minute',
      'duration' => '1 Minute',
      'description' => '',
    ),
    array(
      'id' => 600,
      'casting_time' => '10 Minutes',
      'duration' => '10 Minutes',
      'description' => '',
    ),
    array(
      'id' => 3600,
      'casting_time' => '1 Hour',
      'duration' => '1 Hour',
      'description' => '',
    ),
    array(
      'id' => 7200,
      'casting_time' => '2 Hours',
      'duration' => '2 Hours',
      'description' => '',
    ),
    array(
      'id' => 28800,
      'casting_time' => '8 Hours',
      'duration' => '8 Hours',
      'description' => '',
    ),
    array(
      'id' => 43200,
      'casting_time' => '12 Hours',
      'duration' => '12 Hours',
      'description' => '',
    ),
    array(
      'id' => 86400,
      'casting_time' => '24 Hours',
      'duration' => '24 Hours',
      'description' => '',
    ),
    array(
      'id' => 604800,
      'casting_time' => '7 Days',
      'duration' => '7 Days',
      'description' => '',
    ),
    array(
      'id' => 864000,
      'casting_time' => '10 Days',
      'duration' => '10 Days',
      'description' => '',
    ),
    array(
      'id' => 2592000,
      'casting_time' => '30 Days',
      'duration' => '30 Days',
      'description' => '',
    ),
  );

  foreach ($speeds as $speed)
  {
    createSpeed($speed);
  }
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
