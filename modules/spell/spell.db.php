<?php


/******************************************************************************
 *
 *  Install.
 *
 ******************************************************************************/
function installSpell()
{
  GLOBAL $db;

  // Line, cone, cube, etc.
  $query = new CreateQuery('aoes');
  $query->addField('id', 'INTEGER', 0, array('P', 'A'));
  $query->addField('name', 'TEXT', 32, array('N'));
  $query->addField('description', 'TEXT', 1024);
  $db->create($query);

  // Touch, 5 ft, 60ft, etc.
  $query = new CreateQuery('ranges');
  $query->addField('id', 'INTEGER', 0, array('P', 'U'));
  $query->addField('name', 'TEXT', 32, array('N'));
  $query->addField('description', 'TEXT', 1024);
  $db->create($query);

  // Conjuration, evocation, etc.
  $query = new CreateQuery('schools');
  $query->addField('id', 'INTEGER', 0, array('P', 'A'));
  $query->addField('name', 'TEXT', 32, array('N'));
  $query->addField('description', 'TEXT', 1024);
  $db->create($query);

  // Action, Reaction, 1 minute, etc.
  $query = new CreateQuery('speeds');
  $query->addField('id', 'INTEGER', 0, array('P', 'U'));
  $query->addField('casting_time', 'TEXT', 32, array('N'));
  $query->addField('duration', 'TEXT', 32, array('N'));
  $query->addField('description', 'TEXT', 1024);
  $db->create($query);

  // Spells.
  $query = new CreateQuery('spells');
  $query->addField('id', 'INTEGER', 0, array('P', 'A'));
  $query->addField('name', 'TEXT', 32, array('N'));
  $query->addField('school_id', 'INTEGER', 0, array('N'), 0);
  $query->addField('level', 'INTEGER', 0, array('N'), 0);
  $query->addField('speed', 'INTEGER', 0, array('N'), 0);
  $query->addField('range', 'INTEGER', 0, array('N'), 0);
  $query->addField('ritual', 'INTEGER', 0, array('N'), 0);
  $query->addField('concentration', 'INTEGER', 0, array('N'), 0);
  $query->addField('verbal', 'INTEGER', 0, array('N'), 0);
  $query->addField('semantic', 'INTEGER', 0, array('N'), 0);
  $query->addField('material', 'TEXT', 128, array('N'), 0);
  $query->addField('duration', 'INTEGER', 32, array('N'), 0);
  $query->addField('aoe_id', 'INTEGER', 0, array('N'), 0);
  $query->addField('aoe_range', 'INTEGER', 0, array('N'), 0);
  $query->addField('description', 'TEXT', 1024);
  $query->addField('alternate', 'TEXT', 1024);
  $query->addField('source_id', 'INTEGER', 0, array('N'), 0);
  $query->addField('source_location', 'INTEGER', 0, array('N'), 0);
  $db->create($query);
}

/******************************************************************************
 *
 *  Spell.
 *
 ******************************************************************************/

/**
 * @param int $page
 *
 * @return array|false
 */
function getSpellPager($page = 1)
{
  GLOBAL $db;

  $query = new SelectQuery('spells');
  $query->addField('id');
  $query->addField('name');
  $query->addField('school_id');
  $query->addField('level');
  $query->addField('description');
  $query->addOrderSimple('name');
  $query->addPager($page);

  return $db->select($query);
}

/**
 * @param int $spell_id
 *
 * @return array|false
 */
function getSpell($spell_id)
{
  GLOBAL $db;

  $query = new SelectQuery('spells');
  $query->addField('id');
  $query->addField('name');
  $query->addField('source_id');
  $query->addField('school_id');
  $query->addField('level');
  $query->addField('speed');
  $query->addField('range');
  $query->addField('ritual');
  $query->addField('concentration');
  $query->addField('verbal');
  $query->addField('semantic');
  $query->addField('material');
  $query->addField('duration');
  $query->addField('aoe_id');
  $query->addField('aoe_range');
  $query->addField('description');
  $query->addField('alternate');
  $query->addConditionSimple('id', $spell_id);

  return $db->selectObject($query);
}

/**
 * @param array $spell
 *
 * @return int
 */
function createSpell($spell)
{
  GLOBAL $db;

  $query = new InsertQuery('spells');
  $query->addField('name', $spell['name']);
  $query->addField('source_id', $spell['source_id']);
  $query->addField('school_id', $spell['school_id']);
  $query->addField('level', $spell['level']);
  $query->addField('speed', $spell['speed']);
  $query->addField('range', $spell['range']);
  $query->addField('ritual', $spell['ritual']);
  $query->addField('concentration', $spell['concentration']);
  $query->addField('verbal', $spell['verbal']);
  $query->addField('semantic', $spell['semantic']);
  $query->addField('material', $spell['material']);
  $query->addField('duration', $spell['duration']);
  $query->addField('aoe_id', $spell['aoe_id']);
  $query->addField('aoe_range', $spell['aoe_range']);
  $query->addField('description', $spell['description']);
  $query->addField('alternate', $spell['alternate']);

  return $db->insert($query);
}

/**
 * @param array $spell
 */
function updateSpell($spell)
{
  GLOBAL $db;

  $query = new UpdateQuery('spells');
  $query->addField('name', $spell['name']);
  $query->addField('source_id', $spell['source_id']);
  $query->addField('school_id', $spell['school_id']);
  $query->addField('level', $spell['level']);
  $query->addField('speed', $spell['speed']);
  $query->addField('range', $spell['range']);
  $query->addField('ritual', $spell['ritual']);
  $query->addField('concentration', $spell['concentration']);
  $query->addField('verbal', $spell['verbal']);
  $query->addField('semantic', $spell['semantic']);
  $query->addField('material', $spell['material']);
  $query->addField('duration', $spell['duration']);
  $query->addField('aoe_id', $spell['aoe_id']);
  $query->addField('aoe_range', $spell['aoe_range']);
  $query->addField('description', $spell['description']);
  $query->addField('alternate', $spell['alternate']);
  $query->addConditionSimple('id', $spell['id']);

  $db->update($query);
}

/**
 * @param int $spell_id
 */
function deleteSpell($spell_id)
{
  GLOBAL $db;

  $query = new DeleteQuery('spells');
  $query->addConditionSimple('id', $spell_id);
  $db->delete($query);
}

/******************************************************************************
 *
 *  School
 *
 ******************************************************************************/

/**
 * @param int $page
 *
 * @return array|false
 */
function getSchoolPager($page)
{
  GLOBAL $db;

  $query = new SelectQuery('schools');
  $query->addField('id');
  $query->addField('name');
  $query->addField('description');
  $query->addPager($page);

  return $db->select($query);
}

/**
 * @return array
 */
function getSchoolList()
{
  GLOBAL $db;

  $query = new SelectQuery('schools');
  $query->addField('id')->addField('name', 'value');

  return $db->selectList($query);
}

/**
 * @param int $school_id
 *
 * @return array|false
 */
function getSchool($school_id)
{
  GLOBAL $db;

  $query = new SelectQuery('schools');
  $query->addField('id');
  $query->addField('name');
  $query->addField('description');
  $query->addConditionSimple('id', $school_id);

  return $db->selectObject($query);
}

/**
 * @param array $school
 *
 * @return int
 */
function createSchool($school)
{
  GLOBAL $db;

  $query = new InsertQuery('schools');
  $query->addField('name', $school['name']);
  $query->addField('description', $school['description']);

  return $db->insert($query);
}

/**
 * @param array $school
 */
function updateSchool($school)
{
  GLOBAL $db;

  $query = new updateQuery('schools');
  $query->addField('name', $school['name']);
  $query->addField('description', $school['description']);
  $query->addConditionSimple('id', $school['id']);

  $db->update($query);
}

/**
 * @param int $school_id
 *
 * @return int
 */
function deleteSchool($school_id)
{
  GLOBAL $db;

  $query = new InsertQuery('schools');
  $query->addConditionSimple('id', $school_id);

  return $db->insert($query);
}

/******************************************************************************
 *
 *  AOE
 *
 ******************************************************************************/

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
 *
 * @return int
 */
function deleteAoe($aoe_id)
{
  GLOBAL $db;

  $query = new InsertQuery('aoes');
  $query->addConditionSimple('id', $aoe_id);

  return $db->insert($query);
}

/******************************************************************************
 *
 *  Speed
 *
 ******************************************************************************/
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
 *
 * @return int
 */
function deleteSpeed($speed_id)
{
  GLOBAL $db;

  $query = new InsertQuery('speeds');
  $query->addConditionSimple('id', $speed_id);

  return $db->insert($query);
}

/******************************************************************************
 *
 *  Range.
 *
 ******************************************************************************/

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
