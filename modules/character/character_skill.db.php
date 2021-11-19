<?php

/******************************************************************************
 *
 *  Install.
 *
 ******************************************************************************/
function installCharacterSkill()
{
  GLOBAL $db;

  $query = new CreateQuery('character_skill');
  $query->addField('character_id', CreateQuery::TYPE_INTEGER, 0, array('P', 'N'));
  $query->addField('skill_id', CreateQuery::TYPE_INTEGER, 0, array('P', 'N'));
  $query->addField('proficiency_multiplier', CreateQuery::TYPE_DECIMAL, 0, array('N'), 0);
  $db->create($query);
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

  $query = new SelectQuery('character_skill');
  $query->addField('skill_id');
  $query->addField('proficiency_multiplier');
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

  $query = new SelectQuery('character_skill');
  $query->addField('character_id');
  $query->addField('skill_id');
  $query->addField('proficiency_multiplier');
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

  $query = new InsertQuery('character_skill');
  $query->addField('character_id', $character_skill['character_id']);
  $query->addField('skill_id', $character_skill['skill_id']);
  $query->addField('proficiency_multiplier', $character_skill['proficiency_multiplier']);
  $db->insert($query);
}

/**
 * @param array $character_skill
 */
function updateCharacterSkill($character_skill)
{
  GLOBAL $db;

  $query = new UpdateQuery('character_skill');
  $query->addField('proficiency_multiplier', $character_skill['proficiency_multiplier']);
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

  $query = new DeleteQuery('character_skill');
  $query->addConditionSimple('character_id', $character_skill['character_id']);
  $query->addConditionSimple('skill_id', $character_skill['skill_id']);
  $db->delete($query);
}
