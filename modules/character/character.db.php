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
  $query->addField('id', 'INTEGER', 0, array('P', 'A'));
  $query->addField('name', 'TEXT', 32, array('N'));
  $query->addField('xp', 'INTEGER', 0, array('N'));
  $query->addField('race_id', 'INTEGER', 0, array('N'));
  $query->addField('subrace_id', 'INTEGER', 0);
  $query->addField('alignment', 'TEXT', 8, array('N'));
  $query->addField('pb', 'TEXT', 8, array('N'), 2);
  $query->addField('speed', 'INTEGER', 0, array('N'), 30);
  $query->addField('hp', 'INTEGER', 0, array('N'));
  $query->addField('player_id', 'INTEGER');
  $query->addField('background_id', 'INTEGER', 0, array('N'), 0);
  $query->addField('personality', 'TEXT');
  $query->addField('ideals', 'TEXT');
  $query->addField('bonds', 'TEXT');
  $query->addField('flaws', 'TEXT');
  $query->addField('features', 'TEXT');
  $db->create($query);

  $query = new CreateQuery('character_attribute_map');
  $query->addField('character_id', 'INTEGER', 0, array('P', 'N'));
  $query->addField('attribute_id', 'INTEGER', 0, array('P', 'N'));
  $query->addField('score', 'INTEGER', 0, array('N'), 8);
  $query->addField('modifier', 'INTEGER', 0, array('N'), -1);
  $query->addField('proficiency', 'INTEGER', 0, array('N'), 0);
  $query->addField('saving_throw', 'INTEGER', 0, array('N'), -1);
  $db->create($query);

  $query = new CreateQuery('character_class_map');
  $query->addField('character_id', 'INTEGER', 0, array('P', 'N'));
  $query->addField('class_id', 'INTEGER', 0, array('P', 'N'));
  $query->addField('subclass_id', 'INTEGER', 0, array('N'), 0);
  $query->addField('level', 'INTEGER', 0, array('N'), 0);
  $db->create($query);

  $query = new CreateQuery('character_die_map');
  $query->addField('character_id', 'INTEGER', 0, array('P', 'N'));
  $query->addField('die_id', 'INTEGER', 0, array('P', 'N'));
  $query->addField('die_count', 'INTEGER', 0, array('N'), 0);
  $db->create($query);

  $query = new CreateQuery('character_skill_map');
  $query->addField('character_id', 'INTEGER', 0, array('P', 'N'));
  $query->addField('skill_id', 'INTEGER', 0, array('P', 'N'));
  $query->addField('proficiency', 'INTEGER', 0, array('N'), 0);
  $query->addField('modifier', 'INTEGER', 0, array('N'), 0);
  $db->create($query);

  $query = new CreateQuery('character_language_map');
  $query->addField('character_id', 'INTEGER', 0, array('P', 'N'));
  $query->addField('language_id', 'INTEGER', 0, array('P', 'N'));
  $db->create($query);

  $query = new CreateQuery('character_item_type_proficiency_map');
  $query->addField('character_id', 'INTEGER', 0, array('P', 'N'));
  $query->addField('item_type_id', 'INTEGER', 0, array('P', 'N'));
  $db->create($query);

  $query = new CreateQuery('character_item_proficiency_map');
  $query->addField('character_id', 'INTEGER', 0, array('P', 'N'));
  $query->addField('item_id', 'INTEGER', 0, array('P', 'N'));
  $query->addField('proficiency', 'INTEGER', 0, array('N'), 0);
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
  $query->addField('subrace_id');
  $query->addField('player_id');
  $query->addField('background_id');
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
  $query->addField('subrace_id');
  $query->addField('player_id');
  $query->addField('background_id');
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
  $query->addField('subrace_id', $character['subrace_id']);
  $query->addField('player_id', $character['player_id']);
  $query->addField('background_id', $character['background_id']);
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
  $query->addField('subrace_id', $character['subrace_id']);
  $query->addField('player_id', $character['player_id']);
  $query->addField('background_id', $character['background_id']);
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

  $query = new SelectQuery('character_class_map');
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

  $query = new SelectQuery('character_class_map');
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

  $query = new InsertQuery('character_class_map');
  $query->addField('character_id', $character_class['character_id']);
  $query->addField('class_id', $character_class['class_id']);
  $query->addField('subclass_id', $character_class['subclass_id']);
  $query->addField('level', $character_class['level']);
  $db->insert($query);
}

function updateCharacterClass($character_class)
{
  GLOBAL $db;

  $query = new UpdateQuery('character_class_map');
  $query->addField('subclass_id', $character_class['subclass_id']);
  $query->addField('level', $character_class['level']);
  $query->addConditionSimple('character_id', $character_class['character_id']);
  $query->addConditionSimple('class_id', $character_class['class_id']);
  $db->update($query);
}

function deleteCharacterClass($character_class)
{
  GLOBAL $db;

  $query = new DeleteQuery('character_class_map');
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
function getCharacterAttributeList($character_id)
{
  GLOBAL $db;

  $query = new SelectQuery('character_attribute_map');
  $query->addField('character_id');
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

  $query = new SelectQuery('character_attribute_map');
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

  $query = new InsertQuery('character_attribute_map');
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

  $query = new UpdateQuery('character_attribute_map');
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

  $query = new DeleteQuery('character_attribute_map');
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

  $query = new SelectQuery('character_skill_map');
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

  $query = new SelectQuery('character_skill_map');
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

  $query = new InsertQuery('character_skill_map');
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

  $query = new UpdateQuery('character_skill_map');
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

  $query = new DeleteQuery('character_skill_map');
  $query->addConditionSimple('character_id', $character_skill['character_id']);
  $query->addConditionSimple('skill_id', $character_skill['skill_id']);
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

  $query = new SelectQuery('character_language_map');
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

  $query = new SelectQuery('character_language_map');
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
 * @param array $character_language_map
 */
function createCharacterLanguage($character_language_map)
{
  GLOBAL $db;

  $query = new InsertQuery('character_language_map');
  $query->addField('character_id', $character_language_map['character_id']);
  $query->addField('language_id', $character_language_map['language_id']);
  $db->insert($query);
}

/**
 * @param array $character_language_map
 */
function deleteCharacterLanguage($character_language_map)
{
  GLOBAL $db;

  $query = new DeleteQuery('character_language_map');
  $query->addConditionSimple('character_id', $character_language_map['character_id']);
  $query->addConditionSimple('language_id', $character_language_map['language_id']);
  $db->delete($query);
}

/******************************************************************************
 *
 *  Character Item Types Proficiencies.
 *
 ******************************************************************************/

/**
 * @param int $character_id
 *
 * @return array|false
 */
function getCharacterItemTypeProficiencyList($character_id)
{
  GLOBAL $db;

  $query = new SelectQuery('character_item_type_proficiency_map');
  $query->addField('item_type_id');
  $query->addConditionSimple('character_id', $character_id);
  $query->addOrderSimple('item_type_id', QueryOrder::DIRECTION_ASC);
  $results = $db->select($query);

  if (!$results)
  {
    return array();
  }
  return $results;
}

/**
 * @param int $character_id
 * @param int $item_type_id
 *
 * @return array|mixed
 */
function getCharacterItemTypeProficiency($character_id, $item_type_id)
{
  GLOBAL $db;

  $query = new SelectQuery('character_item_type_proficiency_map');
  $query->addField('character_id');
  $query->addField('item_type_id');
  $query->addConditionSimple('character_id', $character_id);
  $query->addConditionSimple('item_type_id', $item_type_id);
  $results = $db->select($query);

  if (!$results)
  {
    return array();
  }
  $result = array_shift($results);
  return $result;
}

/**
 * @param array $character_item_type_proficiency
 */
function createCharacterItemTypeProficiency($character_item_type_proficiency)
{
  GLOBAL $db;

  $query = new InsertQuery('character_item_type_proficiency_map');
  $query->addField('character_id', $character_item_type_proficiency['character_id']);
  $query->addField('item_type_id', $character_item_type_proficiency['item_type_id']);
  $db->insert($query);
}

/**
 * @param array $character_item_type_proficiency
 */
function deleteCharacterItemTypeProficiency($character_item_type_proficiency)
{
  GLOBAL $db;

  $query = new DeleteQuery('character_item_type_proficiency_map');
  $query->addConditionSimple('character_id', $character_item_type_proficiency['character_id']);
  $query->addConditionSimple('item_type_id', $character_item_type_proficiency['item_type_id']);
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
function getCharacterItemProficiencyList($character_id)
{
  GLOBAL $db;

  $query = new SelectQuery('character_item_proficiency_map');
  $query->addField('item_id');
  $query->addField('proficiency');
  $query->addConditionSimple('character_id', $character_id);
  $query->addOrderSimple('item_id', QueryOrder::DIRECTION_ASC);
  $results = $db->select($query);

  if (!$results)
  {
    return array();
  }
  return $results;
}

/**
 * @param int $character_id
 * @param int $item_id
 *
 * @return array|mixed
 */
function getCharacterItemProficiency($character_id, $item_id)
{
  GLOBAL $db;

  $query = new SelectQuery('character_item_proficiency_map');
  $query->addField('character_id');
  $query->addField('item_id');
  $query->addField('proficiency');
  $query->addConditionSimple('character_id', $character_id);
  $query->addConditionSimple('item_id', $item_id);
  $results = $db->select($query);

  if (!$results)
  {
    return array();
  }
  $result = array_shift($results);
  return $result;
}

/**
 * @param array $character_item_proficiency
 */
function createCharacterItemProficiency($character_item_proficiency)
{
  GLOBAL $db;

  $query = new InsertQuery('character_item_proficiency_map');
  $query->addField('character_id', $character_item_proficiency['character_id']);
  $query->addField('item_id', $character_item_proficiency['item_id']);
  $query->addField('proficiency', $character_item_proficiency['proficiency']);
  $db->insert($query);
}

function updateCharacterItemProficiency($character_item_proficiency)
{
  GLOBAL $db;

  $query = new UpdateQuery('character_item_proficiency_map');
  $query->addField('proficiency', $character_item_proficiency['proficiency']);
  $query->addConditionSimple('character_id', $character_item_proficiency['character_id']);
  $query->addConditionSimple('item_id', $character_item_proficiency['item_id']);

  $db->update($query);
}

/**
 * @param array $character_item_proficiency
 */
function deleteCharacterItemProficiency($character_item_proficiency)
{
  GLOBAL $db;

  $query = new DeleteQuery('character_item_proficiency_map');
  $query->addConditionSimple('character_id', $character_item_proficiency['character_id']);
  $query->addConditionSimple('item_id', $character_item_proficiency['item_id']);
  $db->delete($query);
}

/******************************************************************************
 *
 *  Character Item Types Proficiencies.
 *
 ******************************************************************************/

/**
 * @param int $character_id
 *
 * @return array|false
 */
function getCharacterDieList($character_id)
{
  GLOBAL $db;

  $query = new SelectQuery('character_die_map');
  $query->addField('die_id');
  $query->addField('die_count');
  $query->addConditionSimple('character_id', $character_id);
  $query->addOrderSimple('die_id', QueryOrder::DIRECTION_DESC);
  $results = $db->select($query);

  if (!$results)
  {
    return array();
  }
  return $results;
}

/**
 * @param int $character_id
 * @param int $item_type_id
 *
 * @return array|mixed
 */
function getCharacterDie($character_id, $item_type_id)
{
  GLOBAL $db;

  $query = new SelectQuery('character_die_map');
  $query->addField('character_id');
  $query->addField('die_id');
  $query->addField('die_count');
  $query->addConditionSimple('character_id', $character_id);
  $query->addConditionSimple('die_id', $item_type_id);
  $results = $db->select($query);

  if (!$results)
  {
    return array();
  }
  $result = array_shift($results);
  return $result;
}

/**
 * @param array $character_die
 */
function createCharacterDie($character_die)
{
  GLOBAL $db;

  $query = new InsertQuery('character_die_map');
  $query->addField('character_id', $character_die['character_id']);
  $query->addField('die_id', $character_die['item_type_id']);
  $query->addField('die_count', $character_die['count']);
  $db->insert($query);
}

/**
 * @param array $character_die
 */
function deleteCharacterDieMap($character_die)
{
  GLOBAL $db;

  $query = new DeleteQuery('character_die_map');
  $query->addConditionSimple('character_id', $character_die['character_id']);
  $query->addConditionSimple('die_id', $character_die['die_id']);
  $db->delete($query);
}
