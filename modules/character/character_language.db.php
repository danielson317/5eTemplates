<?php

/******************************************************************************
 *
 *  Install.
 *
 ******************************************************************************/
function installCharacterLanguage()
{
  GLOBAL $db;

  $query = new CreateQuery('character_language');
  $query->addField('character_id', CreateQuery::TYPE_INTEGER, 0, array('P', 'N'));
  $query->addField('language_id', CreateQuery::TYPE_INTEGER, 0, array('P', 'N'));
  $db->create($query);
}

/******************************************************************************
 *
 *  Character Languages.
 *
 ******************************************************************************/

/**
 * @param int $character_id
 *
 * @return array|false
 */
function getCharacterLanguageList($character_id)
{
  GLOBAL $db;

  $query = new SelectQuery('character_language');
  $query->addField('language_id');
  $query->addConditionSimple('character_id', $character_id);
  $query->addOrderSimple('language_id', QueryOrder::DIRECTION_ASC);
  $results = $db->select($query);

  if (!$results)
  {
    return array();
  }
  return $results;
}

/**
 * @param int $character_id
 * @param int $language_id
 *
 * @return array|mixed
 */
function getCharacterLanguage($character_id, $language_id)
{
  GLOBAL $db;

  $query = new SelectQuery('character_language');
  $query->addField('character_id');
  $query->addField('language_id');
  $query->addConditionSimple('character_id', $character_id);
  $query->addConditionSimple('language_id', $language_id);
  $results = $db->select($query);

  if (!$results)
  {
    return array();
  }
  $result = array_shift($results);
  return $result;
}

/**
 * @param array $character_language
 */
function createCharacterLanguage($character_language)
{
  GLOBAL $db;

  $query = new InsertQuery('character_language');
  $query->addField('character_id', $character_language['character_id']);
  $query->addField('language_id', $character_language['language_id']);
  $db->insert($query);
}

/**
 * @param array $character_language
 */
function deleteCharacterLanguage($character_language)
{
  GLOBAL $db;

  $query = new DeleteQuery('character_language');
  $query->addConditionSimple('character_id', $character_language['character_id']);
  $query->addConditionSimple('language_id', $character_language['language_id']);
  $db->delete($query);
}
