<?php

/******************************************************************************
 *
 *  Install.
 *
 ******************************************************************************/
function installCharacter()
{
  GLOBAL $db;

  $query = new CreateQuery('characters');
  $query->addField('id', 'INTEGER', array('P', 'A'));
  $query->addField('name', 'TEXT', array('N'));
  $query->addField('xp', 'INTEGER', array('N', 0));
  $query->addField('race_id', 'INTEGER', array('N'));
  $query->addField('alignment', 'TEXT', array('N'));
  $query->addField('pb', 'TEXT', array('N'), 2);
  $query->addField('speed', 'INTEGER', array('N'), 30);
  $query->addField('hp', 'INTEGER', array('N'));
  $query->addField('player_id', 'INTEGER');
  $query->addField('background', 'TEXT');
  $query->addField('personality', 'TEXT');
  $query->addField('ideals', 'TEXT');
  $query->addField('bonds', 'TEXT');
  $query->addField('flaws', 'TEXT');
  $query->addField('features', 'TEXT');
  $db->create($query);

  $query = new CreateQuery('proficiencies');
  $query->addField('id', 'INTEGER', array('P', 'A'));
  $query->addField('code', 'TEXT', array('N'));
  $query->addField('name', 'TEXT', array('N'));
  $query->addField('proficiency_type_id', 'INTEGER', array('N'));
  $query->addField('description', 'TEXT');
  $db->create($query);

  $query = new CreateQuery('proficiency_types');
  $query->addField('id', 'INTEGER', array('P', 'A'));
  $query->addField('code', 'TEXT', array('N'));
  $query->addField('name', 'TEXT', array('N'));
  $query->addField('description', 'TEXT');
  $db->create($query);

  $query = new CreateQuery('character_classes');
  $query->addField('character_id', 'INTEGER', array('P', 'N'));
  $query->addField('class_id', 'INTEGER', array('P', 'N'));
  $query->addField('subclass_id', 'INTEGER', array('N'), 0);
  $query->addField('level', 'INTEGER', array('N'), 0);
  $db->create($query);

  $query = new CreateQuery('character_attributes');
  $query->addField('character_id', 'INTEGER', array('P', 'N'));
  $query->addField('attribute_id', 'INTEGER', array('P', 'N'));
  $query->addField('score', 'INTEGER', array('N'), 8);
  $query->addField('modifier', 'INTEGER', array('N'), -1);
  $query->addField('proficiency', 'INTEGER', array('N'), 0);
  $query->addField('saving_throw', 'INTEGER', array('N'), -1);
  $db->create($query);

  $query = new CreateQuery('character_skills');
  $query->addField('character_id', 'INTEGER', array('P', 'N'));
  $query->addField('skill_id', 'INTEGER', array('P', 'N'));
  $query->addField('proficiency', 'INTEGER', array('N'), 0);
  $query->addField('modifier', 'INTEGER', array('N'), 0);
  $db->create($query);

  $query = new CreateQuery('character_languages');
  $query->addField('character_id', 'INTEGER', array('P', 'N'));
  $query->addField('language_id', 'INTEGER', array('P', 'N'));
  $db->create($query);

  $query = new CreateQuery('character_proficiency');
  $query->addField('character_id', 'INTEGER', array('P', 'N'));
  $query->addField('proficiency_id', 'INTEGER', array('P', 'N'));
  $db->create($query);

  $query = new CreateQuery('character_item');
  $query->addField('character_id', 'INTEGER', array('P', 'N'));
  $query->addField('item_id', 'INTEGER', array('P', 'N'));
  $db->create($query);

  $query = new CreateQuery('players');
  $query->addField('id', 'INTEGER', array('P', 'A'));
  $query->addField('name', 'TEXT', array('N'));
  $db->create($query);
}

/******************************************************************************
 *
 *  Character.
 *
 ******************************************************************************/

function getCharacterPager($page)
{
  GLOBAL $db;

  $query = new SelectQuery('characters', 'c');
  $query->addField('id');
  $query->addField('name');
  $query->addField('race_id');
  $query->addField('player_id');
  $query->addField('background');
  $query->addPager($page);
  $results = $db->select($query);

  if (!$results)
  {
    return array();
  }
  return $results;
}

function getCharacter($id)
{
  GLOBAL $db;

  $query = new SelectQuery('characters');
  $query->addField('id');
  $query->addField('name');
  $query->addField('race_id');
  $query->addField('player_id');
  $query->addField('background');
  $query->addField('xp');
  $query->addField('alignment');
  $query->addField('hp');
  $query->addField('pb');
  $query->addField('speed');
  $query->addField('personality');
  $query->addField('ideals');
  $query->addField('bonds');
  $query->addField('flaws');
  $query->addField('features');

  $query->addConditionSimple('id', $id);
  $results = $db->select($query);

  if (!$results)
  {
    return FALSE;
  }
  $result = array_shift($results);
  return $result;
}

function createCharacter($character)
{
  GLOBAL $db;

  $query = new InsertQuery('characters');
  $query->addField('name', $character['name']);
  $query->addField('race_id', $character['race_id']);
  $query->addField('player_id', $character['player_id']);
  $query->addField('background', $character['background']);
  $query->addField('xp', $character['xp']);
  $query->addField('alignment', $character['alignment']);
  $query->addField('hp', $character['hp']);
  $query->addField('pb', $character['pb']);
  $query->addField('speed', $character['speed']);
  $query->addField('personality', $character['personality']);
  $query->addField('ideals', $character['ideals']);
  $query->addField('bonds', $character['bonds']);
  $query->addField('flaws', $character['flaws']);
  $query->addField('features', $character['features']);

  return $db->insert($query);
}

function updateCharacter($character)
{
  GLOBAL $db;

  $query = new UpdateQuery('characters');
  $query->addField('name', $character['name']);
  $query->addField('race_id', $character['race_id']);
  $query->addField('player_id', $character['player_id']);
  $query->addField('background', $character['background']);
  $query->addField('xp', $character['xp']);
  $query->addField('alignment', $character['alignment']);
  $query->addField('hp', $character['hp']);
  $query->addField('pb', $character['pb']);
  $query->addField('speed', $character['speed']);
  $query->addField('personality', $character['personality']);
  $query->addField('ideals', $character['ideals']);
  $query->addField('bonds', $character['bonds']);
  $query->addField('flaws', $character['flaws']);
  $query->addField('features', $character['features']);

  $query->addConditionSimple('id', $character['id']);
  $db->update($query);
}

/******************************************************************************
 *
 *  Character Class.
 *
 ******************************************************************************/

function getCharacterClassList($character_id)
{
  GLOBAL $db;

  $query = new SelectQuery('character_classes');
  $query->addField('class_id');
  $query->addField('subclass_id');
  $query->addField('level');
  $query->addConditionSimple('character_id', $character_id);
  $query->addOrderSimple('level', QueryOrder::DIRECTION_DESC);
  $results = $db->select($query);

  if (!$results)
  {
    return array();
  }
  return $results;
}

function getCharacterClass($character_id, $class_id)
{
  GLOBAL $db;

  $query = new SelectQuery('character_classes');
  $query->addField('character_id');
  $query->addField('class_id');
  $query->addField('subclass_id');
  $query->addField('level');
  $query->addConditionSimple('character_id', $character_id);
  $query->addConditionSimple('class_id', $class_id);
  $results = $db->select($query);

  if (!$results)
  {
    return array();
  }
  $result = array_shift($results);
  return $result;
}

function createCharacterClass($character_class)
{
  GLOBAL $db;

  $query = new InsertQuery('character_classes');
  $query->addField('character_id', $character_class['character_id']);
  $query->addField('class_id', $character_class['class_id']);
  $query->addField('subclass_id', $character_class['subclass_id']);
  $query->addField('level', $character_class['level']);
  $db->insert($query);
}

function updateCharacterClass($character_class)
{
  GLOBAL $db;

  $query = new UpdateQuery('character_classes');
  $query->addField('subclass_id', $character_class['subclass_id']);
  $query->addField('level', $character_class['level']);
  $query->addConditionSimple('character_id', $character_class['character_id']);
  $query->addConditionSimple('class_id', $character_class['class_id']);
  $db->update($query);
}

function deleteCharacterClass($character_class)
{
  GLOBAL $db;

  $query = new DeleteQuery('character_classes');
  $query->addConditionSimple('character_id', $character_class['character_id']);
  $query->addConditionSimple('class_id', $character_class['class_id']);
  $db->delete($query);
}

/******************************************************************************
 *
 *  Character Attributes.
 *
 ******************************************************************************/
/**
 * @param int $character_id
 *
 * @return array|bool
 */
function getCharacterAttributes($character_id)
{
  GLOBAL $db;

  $query = new SelectQuery('character_attributes');
  $query->addField('attribute_id');
  $query->addField('score');
  $query->addField('modifier');
  $query->addField('proficiency');
  $query->addField('saving_throw');
  $query->addConditionSimple('character_id', $character_id);
  $query->addOrderSimple('attribute_id', QueryOrder::DIRECTION_ASC);
  $results = $db->select($query);

  if (!$results)
  {
    return array();
  }
  return $results;
}

/**
 * @param int $character_id
 * @param int $attribute_id
 *
 * @return array|mixed
 */
function getCharacterAttribute($character_id, $attribute_id)
{
  GLOBAL $db;

  $query = new SelectQuery('character_attributes');
  $query->addField('character_id');
  $query->addField('attribute_id');
  $query->addField('score');
  $query->addField('modifier');
  $query->addField('proficiency');
  $query->addField('saving_throw');
  $query->addConditionSimple('character_id', $character_id);
  $query->addConditionSimple('attribute_id', $attribute_id);
  $results = $db->select($query);

  if (!$results)
  {
    return array();
  }
  $result = array_shift($results);
  return $result;
}

/**
 * @param array $character_attribute
 */
function createCharacterAttribute($character_attribute)
{
  GLOBAL $db;

  $query = new InsertQuery('character_attributes');
  $query->addField('character_id', $character_attribute['character_id']);
  $query->addField('attribute_id', $character_attribute['attribute_id']);
  $query->addField('score', $character_attribute['score']);
  $query->addField('modifier', $character_attribute['modifier']);
  $query->addField('proficiency', $character_attribute['proficiency']);
  $query->addField('saving_throw', $character_attribute['saving_throw']);
  $db->insert($query);
}

/**
 * @param array $character_attribute
 */
function updateCharacterAttribute($character_attribute)
{
  GLOBAL $db;

  $query = new UpdateQuery('character_attributes');
  $query->addField('score', $character_attribute['score']);
  $query->addField('modifier', $character_attribute['modifier']);
  $query->addField('proficiency', $character_attribute['proficiency']);
  $query->addField('saving_throw', $character_attribute['saving_throw']);
  $query->addConditionSimple('character_id', $character_attribute['character_id']);
  $query->addConditionSimple('attribute_id', $character_attribute['attribute_id']);
  $db->update($query);
}

/**
 * @param array $character_attribute
 */
function deleteCharacterAttribute($character_attribute)
{
  GLOBAL $db;

  $query = new DeleteQuery('character_attributes');
  $query->addConditionSimple('character_id', $character_attribute['character_id']);
  $query->addConditionSimple('attribute_id', $character_attribute['attribute_id']);
  $db->delete($query);
}

/******************************************************************************
 *
 *  Character Skills.
 *
 ******************************************************************************/

/**
 * @param int $character_id
 *
 * @return array|false
 */
function getCharacterSkillList($character_id)
{
  GLOBAL $db;

  $query = new SelectQuery('character_skills');
  $query->addField('skill_id');
  $query->addField('proficiency');
  $query->addField('modifier');
  $query->addConditionSimple('character_id', $character_id);
  $query->addOrderSimple('skill_id', QueryOrder::DIRECTION_ASC);
  $results = $db->select($query);

  if (!$results)
  {
    return array();
  }
  return $results;
}

/**
 * @param int $character_id
 * @param int $skill_id
 *
 * @return array|mixed
 */
function getCharacterSkill($character_id, $skill_id)
{
  GLOBAL $db;

  $query = new SelectQuery('character_skills');
  $query->addField('character_id');
  $query->addField('skill_id');
  $query->addField('proficiency');
  $query->addField('modifier');
  $query->addConditionSimple('character_id', $character_id);
  $query->addConditionSimple('skill_id', $skill_id);
  $results = $db->select($query);

  if (!$results)
  {
    return array();
  }
  $result = array_shift($results);
  return $result;
}

/**
 * @param array $character_skill
 */
function createCharacterSkill($character_skill)
{
  GLOBAL $db;

  $query = new InsertQuery('character_skills');
  $query->addField('character_id', $character_skill['character_id']);
  $query->addField('skill_id', $character_skill['skill_id']);
  $query->addField('proficiency', $character_skill['proficiency']);
  $query->addField('modifier', $character_skill['modifier']);
  $db->insert($query);
}

/**
 * @param array $character_skill
 */
function updateCharacterSkill($character_skill)
{
  GLOBAL $db;

  $query = new UpdateQuery('character_skills');
  $query->addField('proficiency', $character_skill['proficiency']);
  $query->addField('modifier', $character_skill['modifier']);
  $query->addConditionSimple('character_id', $character_skill['character_id']);
  $query->addConditionSimple('skill_id', $character_skill['skill_id']);
  $db->update($query);
}

/**
 * @param array $character_skill
 */
function deleteCharacterSkill($character_skill)
{
  GLOBAL $db;

  $query = new DeleteQuery('character_skills');
  $query->addConditionSimple('character_id', $character_skill['character_id']);
  $query->addConditionSimple('character_id', $character_skill['skill_id']);
  $db->delete($query);
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

  $query = new SelectQuery('character_languages');
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

  $query = new SelectQuery('character_languages');
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

  $query = new InsertQuery('character_languages');
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

  $query = new DeleteQuery('character_languages');
  $query->addConditionSimple('character_id', $character_language['character_id']);
  $query->addConditionSimple('language_id', $character_language['language_id']);
  $db->delete($query);
}
