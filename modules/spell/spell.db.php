<?php


/******************************************************************************
 *
 *  Spell.
 *
 ******************************************************************************/
function installSpell()
{
  GLOBAL $db;

  $query = new CreateQuery('spells');
  $query->addField('id', 'INTEGER', array('P', 'A'));
  $query->addField('name', 'TEXT', array('N'));
  $query->addField('source_id', 'INTEGER', array('N'), 0);
  $query->addField('school_id', 'INTEGER', array('N'), 0);
  $query->addField('level', 'INTEGER', array('N'), 0);
  $query->addField('speed', 'INTEGER', array('N'), 0);
  $query->addField('range', 'INTEGER', array('N'), 0);
  $query->addField('ritual', 'INTEGER', array('N'), 0);
  $query->addField('concentration', 'INTEGER', array('N'), 0);
  $query->addField('verbal', 'INTEGER', array('N'), 0);
  $query->addField('semantic', 'INTEGER', array('N'), 0);
  $query->addField('material', 'TEXT', array('N'), 0);
  $query->addField('duration', 'INTEGER', array('N'), 0);
  $query->addField('aoe_id', 'INTEGER', array('N'), 0);
  $query->addField('aoe_range', 'INTEGER', array('N'), 0);
  $query->addField('description', 'TEXT');
  $query->addField('alternate', 'TEXT');
  $db->create($query);

  $query = new CreateQuery('schools');
  $query->addField('id', 'INTEGER', array('P', 'A'));
  $query->addField('name', 'TEXT', array('N'));
  $query->addField('description', 'TEXT');
  $db->create($query);

  $query = new CreateQuery('aoes');
  $query->addField('id', 'INTEGER', array('P', 'A'));
  $query->addField('name', 'TEXT', array('N'));
  $query->addField('description', 'TEXT');
  $db->create($query);

  $query = new CreateQuery('damage_types');
  $query->addField('id', 'INTEGER', array('P', 'A'));
  $query->addField('code', 'TEXT', array('N'));
  $query->addField('name', 'TEXT', array('N'));
  $query->addField('description', 'TEXT');
  $db->create($query);

  $query = new CreateQuery('speeds');
  $query->addField('id', 'INTEGER', array('P', 'U'));
  $query->addField('casting_time', 'TEXT', array('N'));
  $query->addField('duration', 'TEXT', array('N'));
  $query->addField('description', 'TEXT');
  $db->create($query);

  $query = new CreateQuery('ranges');
  $query->addField('id', 'INTEGER', array('P', 'U'));
  $query->addField('name', 'TEXT', array('N'));
  $query->addField('description', 'TEXT');
  $db->create($query);
}

function getSpellPager($page = 1)
{
  GLOBAL $db;

  $query = new SelectQuery('spells');
  $query->addField('id')
    ->addField('name')
    ->addField('school_id')
    ->addField('level')
    ->addField('description');
  $query->addOrder('name');
  $query->addPager($page);

  $results = $db->select($query);
  if (!$results)
  {
    return array();
  }
  return $results;
}

function getSpell($id)
{
  GLOBAL $db;

  $query = new SelectQuery('spells');
  $query->addField('id')
    ->addField('name')
    ->addField('source_id')
    ->addField('school_id')
    ->addField('level')
    ->addField('speed')
    ->addField('range')
    ->addField('ritual')
    ->addField('concentration')
    ->addField('verbal')
    ->addField('semantic')
    ->addField('material')
    ->addField('duration')
    ->addField('aoe_id')
    ->addField('aoe_range')
    ->addField('description')
    ->addField('alternate');
  $query->addConditionSimple('id', $id);

  $results = $db->select($query);
  if (!$results)
  {
    return FALSE;
  }
  $result = array_shift($results);
  return $result;
}

function createSpell($spell)
{
  GLOBAL $db;

  $query = new InsertQuery('spells');
  $query->addField('name')
    ->addField('source_id')
    ->addField('school_id')
    ->addField('level')
    ->addField('speed')
    ->addField('range')
    ->addField('ritual')
    ->addField('concentration')
    ->addField('verbal')
    ->addField('semantic')
    ->addField('material')
    ->addField('duration')
    ->addField('aoe_id')
    ->addField('aoe_range')
    ->addField('description')
    ->addField('alternate');
  $args = SQLite::buildArgs($spell);

  return $db->insert($query, $args);
}

function updateSpell($spell)
{
  GLOBAL $db;

  $query = new UpdateQuery('spells');
  $query->addField('name')
    ->addField('source_id')
    ->addField('school_id')
    ->addField('level')
    ->addField('speed')
    ->addField('range')
    ->addField('ritual')
    ->addField('concentration')
    ->addField('verbal')
    ->addField('semantic')
    ->addField('material')
    ->addField('duration')
    ->addField('aoe_id')
    ->addField('aoe_range')
    ->addField('description')
    ->addField('alternate');
  $query->addConditionSimple('id', $spell['id']);

  $db->update($query);
}

function deleteSpell($id)
{

}

/******************************************************************************
 *
 *  Lists.
 *
 ******************************************************************************/

function getSchoolList()
{
  GLOBAL $db;

  $query = new SelectQuery('schools');
  $query->addField('id')->addField('name', 'value');

  return $db->selectList($query);
}

function getAoeList()
{
  GLOBAL $db;

  $query = new SelectQuery('aoes');
  $query->addField('id')->addField('name', 'value');

  return $db->selectList($query);
}

function getCastingTimeList()
{
  GLOBAL $db;

  $query = new SelectQuery('speeds');
  $query->addField('id')->addField('casting_time', 'value');
  return $db->selectList($query);
}

function getDurationList()
{
  GLOBAL $db;

  $query = new SelectQuery('speeds');
  $query->addField('id')->addField('duration', 'value');
  $query->addConditionSimple('duration', '', $query::COMPARE_NOT_EQUAL);

  return $db->selectList($query);
}

function getRangeList()
{
  GLOBAL $db;

  $query = new SelectQuery('ranges');
  $query->addField('id')->addField('name', 'value');

  return $db->selectList($query);
}
