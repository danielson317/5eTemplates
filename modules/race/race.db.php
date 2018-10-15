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

  $query = new CreateQuery('scripts');
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
  $query->addOrderSimple('name');
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
  $query->addOrderSimple('name');

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
  $query->addConditionSimple('id', $id);

  $results = $db->select($query);
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
  $query->addConditionSimple('id', $id);

  $db->update($query);
}

function deleteRace($id)
{
  GLOBAL $db;

  $query = new DeleteQuery('races');
  $query->addConditionSimple('id', $id);

  $db->delete($query);
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
  $query->addConditionSimple('race_id', $race_id);
  $query->addPager($page);
  $results = $db->select($query);

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
    $query->addConditionSimple('race_id', $race_id);
  }

  return $db->selectList($query);
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
  $query->addConditionSimple('id', $id);

  $results = $db->select($query);
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
  $query->addConditionSimple('id', $race['id']);

  $db->update($query);
}

function deleteSubrace($id)
{
  GLOBAL $db;

  $query = new DeleteQuery('subraces');
  $query->addConditionSimple('id', $id);

  $db->delete($query);
}

/******************************************************************************
 *
 *  Script.
 *
 ******************************************************************************/
function getScriptPager($page = 1)
{
  GLOBAL $db;

  $query = new SelectQuery('scripts');
  $query->addField('id');
  $query->addField('name');
  $query->addField('source_id');
  $query->addField('description');
  $query->addPager($page);

  $results = $db->select($query);

  if (!$results)
  {
    return array();
  }
  return $results;
}

function getScriptList()
{
  GLOBAL $db;

  $query = new SelectQuery('scripts');
  $query->addField('id')->addField('name', 'value');

  return $db->selectList($query);
}

function getScript($id)
{
  GLOBAL $db;

  $query = new SelectQuery('scripts');
  $query->addField('id');
  $query->addField('name');
  $query->addField('source_id');
  $query->addField('description');
  $query->addConditionSimple('id', $id);
  $results = $db->select($query);
  if (!$results)
  {
    return FALSE;
  }
  $result = array_shift($results);
  return $result;
}

function createScript($script)
{
  GLOBAL $db;

  $query = new InsertQuery('scripts');
  $query->addField('name');
  $query->addField('source_id');
  $query->addField('description');
  $args = $db->buildArgs($script);

  return $db->insert($query, $args);
}

function updateScript($script)
{
  GLOBAL $db;

  $query = new UpdateQuery('scripts');
  $query->addField('name');
  $query->addField('source_id');
  $query->addField('description');
  $query->addConditionSimple('id', $script['id']);

  $db->update($query);
}

function deleteScript($id)
{
  GLOBAL $db;

  $query = new DeleteQuery('scripts');
  $query->addConditionSimple('id', $id);

  $db->delete($query);
}

/******************************************************************************
 *
 *  Language.
 *
 ******************************************************************************/
function getLanguagePager($page = 1)
{
  GLOBAL $db;

  $query = new SelectQuery('languages');
  $query->addField('id');
  $query->addField('name');
  $query->addField('source_id');
  $query->addField('script_id');
  $query->addField('description');

  // Join scripts.
//  $condition = new QueryCondition('script_id', 'languages');
//  $condition->setValueField('id', 'scripts');
//  $table = new QueryTable('scripts', 'scripts', QueryTable::INNER_JOIN, $condition);
//  $query->addTable($table);

  // Filter based on script text.
//  $condition = new QueryCondition('name', 'scripts', QueryCondition::COMPARE_EQUAL, 'Dwarvish');
//  $query->addCondition($condition);

//  $query->addCondition(new QueryCondition('id', 'languages', QueryCondition::COMPARE_GREATER_THAN, 1));
//  $query->addCondition(new QueryCondition('script_id', 'languages', QueryCondition::COMPARE_GREATER_THAN, 1));
//  $condition = new QueryCondition('id', 'languages', QueryCondition::COMPARE_EQUAL, FALSE);
//  $condition->setValueField('script_id', 'languages');
//  $query->addCondition($condition);
  $query->addPager($page);

  $results = $db->select($query);

  if (!$results)
  {
    return array();
  }
  return $results;
}

function getLanguageList()
{
  GLOBAL $db;

  $query = new SelectQuery('languages');
  $query->addField('id')->addField('name', 'value');

  return $db->selectList($query);
}

function getLanguage($id)
{
  GLOBAL $db;

  $query = new SelectQuery('languages');
  $query->addField('id');
  $query->addField('name');
  $query->addField('source_id');
  $query->addField('script_id');
  $query->addField('description');
  $query->addConditionSimple('id', $id);

  $results = $db->select($query);
  if (!$results)
  {
    return FALSE;
  }
  $result = array_shift($results);
  return $result;
}

function createLanguage($language)
{
  GLOBAL $db;

  $query = new InsertQuery('languages');
  $query->addField('name');
  $query->addField('source_id');
  $query->addField('script_id');
  $query->addField('description');
  $args = $db->buildArgs($language);

  return $db->insert($query, $args);
}

function updateLanguage($language)
{
  GLOBAL $db;

  $query = new UpdateQuery('languages');
  $query->addField('name');
  $query->addField('source_id');
  $query->addField('script_id');
  $query->addField('description');
  $query->addConditionSimple('id', $language['id']);

  $db->update($query);
}

function deleteLanguage($id)
{
  GLOBAL $db;

  $query = new DeleteQuery('languages');
  $query->addCondition('id');
  $args = array(':id' => $id);

  $db->delete($query, $args);
}
