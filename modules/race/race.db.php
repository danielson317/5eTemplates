<?php

function installRace()
{
  GLOBAL $db;

  $query = new CreateQuery('races');
  $query->addField('id', 'INTEGER', 0, array('P', 'A'));
  $query->addField('name', 'TEXT', 32, array('N'));
  $query->addField('description', 'TEXT', 1024);
  $query->addField('speed', 'INTEGER', 0, array('N'), 30);
  $query->addField('source_id', 'INTEGER');
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
  $query->addConditionSimple('id', $language_id);

  $db->delete($query);
}
