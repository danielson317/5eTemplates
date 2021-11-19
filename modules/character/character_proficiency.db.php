<?php

/******************************************************************************
 *
 *  Install.
 *
 ******************************************************************************/
function installCharacterProficiency()
{
  GLOBAL $db;

  $query = new CreateQuery('character_proficiency');
  $query->addField('character_id', CreateQuery::TYPE_INTEGER, 0, array('P', 'N'));
  $query->addField('item_id', CreateQuery::TYPE_INTEGER, 0, array('P', 'N'));
  $query->addField('multiplier', CreateQuery::TYPE_DECIMAL, 0, array('N'), 0);
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
function getCharacterProficiencyList(int $character_id)
{
  GLOBAL $db;

  $query = new SelectQuery('character_proficiency');
  $query->addField('item_id');
  $query->addField('multiplier');
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
function getCharacterProficiency($character_id, $item_id)
{
  GLOBAL $db;

  $query = new SelectQuery('character_proficiency');
  $query->addField('character_id');
  $query->addField('item_id');
  $query->addField('multiplier');
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
function createCharacterProficiency($character_item_proficiency)
{
  GLOBAL $db;

  $query = new InsertQuery('character_proficiency');
  $query->addField('character_id', $character_item_proficiency['character_id']);
  $query->addField('item_id', $character_item_proficiency['item_id']);
  $query->addField('multiplier', $character_item_proficiency['multiplier']);
  $db->insert($query);
}

function updateCharacterProficiency($character_item_proficiency)
{
  GLOBAL $db;

  $query = new UpdateQuery('character_proficiency');
  $query->addField('multiplier', $character_item_proficiency['multiplier']);
  $query->addConditionSimple('character_id', $character_item_proficiency['character_id']);
  $query->addConditionSimple('item_id', $character_item_proficiency['item_id']);

  $db->update($query);
}

/**
 * @param array $character_item_proficiency
 */
function deleteCharacterProficiency($character_item_proficiency)
{
  GLOBAL $db;

  $query = new DeleteQuery('character_proficiency');
  $query->addConditionSimple('character_id', $character_item_proficiency['character_id']);
  $query->addConditionSimple('item_id', $character_item_proficiency['item_id']);
  $db->delete($query);
}
