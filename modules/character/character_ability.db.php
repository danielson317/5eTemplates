<?php

/******************************************************************************
 *
 *  Install.
 *
 ******************************************************************************/
function installCharacterAbility()
{
  GLOBAL $db;

  $query = new CreateQuery('character_ability');
  $query->addField('character_id', CreateQuery::TYPE_INTEGER, 0, array('P', 'N'));
  $query->addField('ability_id', CreateQuery::TYPE_INTEGER, 0, array('P', 'N'));
  $query->addField('score', CreateQuery::TYPE_INTEGER, 0, array('N'), 8);
  $query->addField('proficiency_multiplier', CreateQuery::TYPE_DECIMAL, 0, array('N'), 0);
  $db->create($query);
}

/******************************************************************************
 *
 *  Character abilities.
 *
 ******************************************************************************/
/**
 * @param int $character_id
 *
 * @return array|bool
 */
function getCharacterAbilityList($character_id)
{
  GLOBAL $db;

  $query = new SelectQuery('character_ability');
  $query->addField('character_id');
  $query->addField('ability_id');
  $query->addField('score');
  $query->addField('proficiency_multiplier');
  $query->addConditionSimple('character_id', $character_id);
  $query->addOrderSimple('ability_id', QueryOrder::DIRECTION_ASC);
  $results = $db->select($query);

  if (!$results)
  {
    return array();
  }
  return $results;
}

/**
 * @param int $character_id
 * @param int $ability_id
 *
 * @return array|mixed
 */
function getCharacterAbility($character_id, $ability_id)
{
  GLOBAL $db;

  $query = new SelectQuery('character_ability');
  $query->addField('character_id');
  $query->addField('ability_id');
  $query->addField('score');
  $query->addField('proficiency_multiplier');
  $query->addConditionSimple('character_id', $character_id);
  $query->addConditionSimple('ability_id', $ability_id);
  $results = $db->select($query);

  if (!$results)
  {
    return array();
  }
  $result = array_shift($results);
  return $result;
}

/**
 * @param array $character_ability
 */
function createCharacterAbility($character_ability)
{
  GLOBAL $db;

  $query = new InsertQuery('character_ability');
  $query->addField('character_id', $character_ability['character_id']);
  $query->addField('ability_id', $character_ability['ability_id']);
  $query->addField('score', $character_ability['score']);
  $query->addField('proficiency_multiplier', $character_ability['proficiency_multiplier']);
  $db->insert($query);
}

/**
 * @param array $character_ability
 */
function updateCharacterAbility($character_ability)
{
  GLOBAL $db;

  $query = new UpdateQuery('character_ability');
  $query->addField('score', $character_ability['score']);
  $query->addField('proficiency_multiplier', $character_ability['proficiency_multiplier']);
  $query->addConditionSimple('character_id', $character_ability['character_id']);
  $query->addConditionSimple('ability_id', $character_ability['ability_id']);
  $db->update($query);
}

/**
 * @param array $character_ability
 */
function deleteCharacterAbility($character_ability)
{
  GLOBAL $db;

  $query = new DeleteQuery('character_ability');
  $query->addConditionSimple('character_id', $character_ability['character_id']);
  $query->addConditionSimple('ability_id', $character_ability['ability_id']);
  $db->delete($query);
}
