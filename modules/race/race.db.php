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

/**
 * @param int $page
 *
 * @return array|false
 */
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

  return $db->select($query);
}

/**
 * @return array
 */
function getRaceList()
{
  GLOBAL $db;

  $query = new SelectQuery('races');
  $query->addField('id');
  $query->addField('name', 'value');
  $query->addOrderSimple('name');

  return $db->selectList($query);
}

/**
 * @param int $race_id
 *
 * @return array|false
 */
function getRace($race_id)
{
  GLOBAL $db;

  $query = new SelectQuery('races');
  $query->addField('id');
  $query->addField('name');
  $query->addField('speed');
  $query->addField('description');
  $query->addField('source_id');
  $query->addConditionSimple('id', $race_id);

  return $db->selectObject($query);
}

/**
 * @param array $race
 *
 * @return int
 */
function createRace($race)
{
  GLOBAL $db;

  $query = new InsertQuery('races');
  $query->addField('name', $race['name']);
  $query->addField('speed', $race['speed']);
  $query->addField('description', $race['description']);
  $query->addField('source_id', $race['source_id']);

  return $db->insert($query);
}

/**
 * @param array $race
 */
function updateRace($race)
{
  GLOBAL $db;

  $query = new UpdateQuery('races');
  $query->addField('name', $race['name']);
  $query->addField('speed', $race['speed']);
  $query->addField('description', $race['description']);
  $query->addField('source_id', $race['source_id']);
  $query->addConditionSimple('id', $race['id']);

  $db->update($query);
}

/**
 * @param $race_id
 */
function deleteRace($race_id)
{
  GLOBAL $db;

  $query = new DeleteQuery('races');
  $query->addConditionSimple('id', $race_id);

  $db->delete($query);
}

/******************************************************************************
 *
 *  Subrace.
 *
 ******************************************************************************/

/**
 * @param int $race_id
 * @param int $page
 *
 * @return array|false
 */
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
  return $db->select($query);
}

/**
 * @param int|bool $race_id
 *
 * @return array
 */
function getSubraceList($race_id = FALSE)
{
  GLOBAL $db;

  $query = new SelectQuery('subraces');
  $query->addField('id')->addField('name', 'value');
  if ($race_id)
  {
    $query->addConditionSimple('race_id', $race_id);
  }

  return $db->selectList($query);
}

/**
 * @param $subrace_id
 *
 * @return array|false
 */
function getSubrace($subrace_id)
{
  GLOBAL $db;

  $query = new SelectQuery('subraces');
  $query->addField('id');
  $query->addField('race_id');
  $query->addField('name');
  $query->addField('description');
  $query->addField('source_id');
  $query->addConditionSimple('id', $subrace_id);

  return $db->selectObject($query);
}

/**
 * @param array $subrace
 *
 * @return int
 */
function createSubrace($subrace)
{
  GLOBAL $db;

  $query = new InsertQuery('subraces');
  $query->addField('race_id', $subrace['race_id']);
  $query->addField('name', $subrace['name']);
  $query->addField('description', $subrace['description']);
  $query->addField('source_id', $subrace['source_id']);

  return $db->insert($query);
}

/**
 * @param array $subrace
 */
function updateSubrace($subrace)
{
  GLOBAL $db;

  $query = new UpdateQuery('subraces');
  $query->addField('name', $subrace['name']);
  $query->addField('description', $subrace['description']);
  $query->addField('source_id', $subrace['source_id']);
  $query->addConditionSimple('id', $subrace['id']);

  $db->update($query);
}

/**
 * @param int $subrace_id
 */
function deleteSubrace($subrace_id)
{
  GLOBAL $db;

  $query = new DeleteQuery('subraces');
  $query->addConditionSimple('id', $subrace_id);

  $db->delete($query);
}

/******************************************************************************
 *
 *  Script.
 *
 ******************************************************************************/

/**
 * @param int $page
 *
 * @return array|false
 */
function getScriptPager($page = 1)
{
  GLOBAL $db;

  $query = new SelectQuery('scripts');
  $query->addField('id');
  $query->addField('name');
  $query->addField('source_id');
  $query->addField('description');
  $query->addPager($page);

  return $db->select($query);
}

/**
 * @return array
 */
function getScriptList()
{
  GLOBAL $db;

  $query = new SelectQuery('scripts');
  $query->addField('id')->addField('name', 'value');

  return $db->selectList($query);
}

/**
 * @param int $script_id
 *
 * @return array|false
 */
function getScript($script_id)
{
  GLOBAL $db;

  $query = new SelectQuery('scripts');
  $query->addField('id');
  $query->addField('name');
  $query->addField('source_id');
  $query->addField('description');
  $query->addConditionSimple('id', $script_id);
  return $db->selectObject($query);
}

/**
 * @param array $script
 *
 * @return int
 */
function createScript($script)
{
  GLOBAL $db;

  $query = new InsertQuery('scripts');
  $query->addField('name', $script['name']);
  $query->addField('source_id', $script['source_id']);
  $query->addField('description', $script['description']);

  return $db->insert($query);
}

/**
 * @param array $script
 */
function updateScript($script)
{
  GLOBAL $db;

  $query = new UpdateQuery('scripts');
  $query->addField('name', $script['name']);
  $query->addField('source_id', $script['source_id']);
  $query->addField('description', $script['description']);
  $query->addConditionSimple('id', $script['id']);

  $db->update($query);
}

/**
 * @param int $script_id
 */
function deleteScript($script_id)
{
  GLOBAL $db;

  $query = new DeleteQuery('scripts');
  $query->addConditionSimple('id', $script_id);

  $db->delete($query);
}

/******************************************************************************
 *
 *  Language.
 *
 ******************************************************************************/

/**
 * @param int $page
 *
 * @return array|false
 */
function getLanguagePager($page = 1)
{
  GLOBAL $db;

  $query = new SelectQuery('languages');
  $query->addField('id');
  $query->addField('name');
  $query->addField('source_id');
  $query->addField('script_id');
  $query->addField('description');

  $query->addPager($page);

  return $db->select($query);
}

/**
 * @return array
 */
function getLanguageList()
{
  GLOBAL $db;

  $query = new SelectQuery('languages');
  $query->addField('id')->addField('name', 'value');

  return $db->selectList($query);
}

/**
 * @param int $language_id
 *
 * @return array|false
 */
function getLanguage($language_id)
{
  GLOBAL $db;

  $query = new SelectQuery('languages');
  $query->addField('id');
  $query->addField('name');
  $query->addField('source_id');
  $query->addField('script_id');
  $query->addField('description');
  $query->addConditionSimple('id', $language_id);

  return $db->selectObject($query);
}

/**
 * @param array $language
 *
 * @return int
 */
function createLanguage($language)
{
  GLOBAL $db;

  $query = new InsertQuery('languages');
  $query->addField('name', $language['name']);
  $query->addField('source_id', $language['source_id']);
  $query->addField('script_id', $language['script_id']);
  $query->addField('description', $language['description']);

  return $db->insert($query);
}

/**
 * @param array $language
 */
function updateLanguage($language)
{
  GLOBAL $db;

  $query = new UpdateQuery('languages');
  $query->addField('name', $language['name']);
  $query->addField('source_id', $language['source_id']);
  $query->addField('script_id', $language['script_id']);
  $query->addField('description', $language['description']);
  $query->addConditionSimple('id', $language['id']);

  $db->update($query);
}

/**
 * @param int $language_id
 */
function deleteLanguage($language_id)
{
  GLOBAL $db;

  $query = new DeleteQuery('languages');
  $query->addCondition('id', $language_id);

  $db->delete($query);
}
