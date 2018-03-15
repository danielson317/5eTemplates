<?php

/******************************************************************************
 *
 *  Character.
 *
 ******************************************************************************/
function installRace()
{
  GLOBAL $db;

  $query = new CreateQuery('races');
  $query->addField('id', 'INTEGER', array('P', 'A'));
  $query->addField('name', 'TEXT', array('N'));
  $query->addField('description', 'TEXT');
  $query->addField('speed', 'INTEGER', array('N'), 30);
  $query->addField('source_id', 'INTEGER');
  $db->create($query);

  $query = new CreateQuery('subraces');
  $query->addField('id', 'INTEGER', array('P', 'A'));
  $query->addField('name', 'TEXT', array('N'));
  $query->addField('race_id', 'INTEGER', array('N'));
  $query->addField('source_id', 'INTEGER');
  $query->addField('description', 'TEXT');
  $db->create($query);

  $query = new CreateQuery('names');
  $query->addField('id', 'INTEGER', array('P', 'A'));
  $query->addField('name', 'TEXT', array('N'));
  $query->addField('race_id', 'INTEGER');
  $query->addField('source_id', 'INTEGER');
  $query->addField('description', 'TEXT');
  $query->addField('placement', 'TEXT', array('N'), 'F'); // First, Last, Middle
  $db->create($query);

  $query = new CreateQuery('languages');
  $query->addField('id', 'INTEGER', array('P', 'A'));
  $query->addField('name', 'TEXT', array('N'));
  $query->addField('script_id', 'INTEGER');
  $query->addField('source_id', 'INTEGER');
  $query->addField('description', 'TEXT');
  $db->create($query);

  $query = new CreateQuery('script');
  $query->addField('id', 'INTEGER', array('P', 'A'));
  $query->addField('name', 'TEXT', array('N'));
  $query->addField('source_id', 'INTEGER');
  $query->addField('description', 'TEXT');
  $db->create($query);
}

/******************************************************************************
 *
 *  Race.
 *
 ******************************************************************************/

function getRacePager($page = 1)
{
  GLOBAL $db;

  $query = new SelectQuery('races');
  $query->addField('id');
  $query->addField('name');
  $query->addField('speed');
  $query->addField('description');
  $query->addField('source_id');
  $query->addOrder('name');
  $query->addPager($page);

  $results = $db->select($query);

  if (!$results)
  {
    return array();
  }
  return $results;
}

function getRaceList()
{
  GLOBAL $db;

  $query = new SelectQuery('races');
  $query->addField('id');
  $query->addField('name', 'value');
  $query->addOrder('name');

  return $db->selectList($query);
}

function getRace($id)
{
  GLOBAL $db;

  $query = new SelectQuery('races');
  $query->addField('id');
  $query->addField('name');
  $query->addField('speed');
  $query->addField('description');
  $query->addField('source_id');
  $query->addCondition('id');
  $args = array(':id' => $id);

  $results = $db->select($query, $args);
  if (!$results)
  {
    return FALSE;
  }
  $result = array_shift($results);
  return $result;
}

function createRace($race)
{
  GLOBAL $db;

  $query = new InsertQuery('races');
  $query->addField('name');
  $query->addField('speed');
  $query->addField('description');
  $query->addField('source_id');

  $args = $db->buildArgs($race);

  return $db->insert($query, $args);
}

function updateRace($race)
{
  GLOBAL $db;

  $query = new UpdateQuery('races');
  $query->addField('name');
  $query->addField('speed');
  $query->addField('description');
  $query->addField('source_id');
  $query->addCondition('id');
  $args = $db->buildArgs($race);

  $db->update($query, $args);
}

function deleteRace($id)
{
  GLOBAL $db;

  $query = new DeleteQuery('races');
  $query->addCondition('id');
  $args = array(':id' => $id);

  $db->delete($query, $args);
}

/******************************************************************************
 *
 *  Subrace.
 *
 ******************************************************************************/

function getSubracePager($race_id, $page = 1)
{
  GLOBAL $db;

  $query = new SelectQuery('subraces');
  $query->addField('id');
  $query->addField('name');
  $query->addField('description');
  $query->addField('source_id');
  $query->addCondition('race_id');
  $query->addPager($page);
  $args = array(':race_id' => $race_id);

  $results = $db->select($query, $args);

  if (!$results)
  {
    return array();
  }
  return $results;
}

function getSubraceList($race_id = FALSE)
{
  GLOBAL $db;

  $query = new SelectQuery('subraces');
  $query->addField('id')->addField('name', 'value');
  $args = array();
  if ($race_id)
  {
    $query->addCondition('race_id');
    $args[':race_id'] = $race_id;
  }

  return $db->selectList($query, $args);
}

function getSubrace($id)
{
  GLOBAL $db;

  $query = new SelectQuery('subraces');
  $query->addField('id');
  $query->addField('race_id');
  $query->addField('name');
  $query->addField('description');
  $query->addField('source_id');
  $query->addCondition('id');
  $args = array(':id' => $id);

  $results = $db->select($query, $args);
  if (!$results)
  {
    return FALSE;
  }
  $result = array_shift($results);
  return $result;
}

function createSubrace($race)
{
  GLOBAL $db;

  $query = new InsertQuery('subraces');
  $query->addField('race_id');
  $query->addField('name');
  $query->addField('description');
  $query->addField('source_id');
  $args = $db->buildArgs($race);

  return $db->insert($query, $args);
}

function updateSubrace($race)
{
  GLOBAL $db;

  $query = new UpdateQuery('subraces');
  $query->addField('name');
  $query->addField('description');
  $query->addField('source_id');
  $query->addCondition('id');
  $args = $db->buildArgs($race);

  $db->update($query, $args);
}

function deleteSubrace($id)
{
  GLOBAL $db;

  $query = new DeleteQuery('subraces');
  $query->addCondition('id');
  $args = array(':id' => $id);

  $db->delete($query, $args);
}
