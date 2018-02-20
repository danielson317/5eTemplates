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

  $query = new CreateQuery('aoes');
  $query->addField('id', 'INTEGER', array('P', 'A'));
  $query->addField('name', 'TEXT', array('N'));
  $query->addField('description', 'TEXT');
  $db->create($query);

  $query = new CreateQuery('aoes');
  $query->addField('id', 'INTEGER', array('P', 'A'));
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
  $query->addOrder('school_id');
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
  $query->addCondition('id', ':id');
  $args = array(
    ':id' => $id,
  );

  $results = $db->select($query, $args);
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
  $query->addCondition('id');
  $args = SQLite::buildArgs($spell);

  $db->update($query, $args);
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
