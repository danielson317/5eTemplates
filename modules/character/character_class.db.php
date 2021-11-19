<?php

/******************************************************************************
 *
 *  Install.
 *
 ******************************************************************************/
function installCharacterClass()
{
  GLOBAL $db;

  $query = new CreateQuery('character_class');
  $query->addField('character_id', CreateQuery::TYPE_INTEGER, 0, array('P', 'N'));
  $query->addField('class_id', CreateQuery::TYPE_INTEGER, 0, array('P', 'N'));
  $query->addField('subclass_id', CreateQuery::TYPE_INTEGER, 0, array('N'), 0);
  $query->addField('level', CreateQuery::TYPE_INTEGER, 0, array('N'), 0);
  $db->create($query);

}

/******************************************************************************
 *
 *  Character Class.
 *
 ******************************************************************************/

function getCharacterClassList($character_id)
{
  GLOBAL $db;

  $query = new SelectQuery('character_class');
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

  $query = new SelectQuery('character_class');
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

  $query = new InsertQuery('character_class');
  $query->addField('character_id', $character_class['character_id']);
  $query->addField('class_id', $character_class['class_id']);
  $query->addField('subclass_id', $character_class['subclass_id']);
  $query->addField('level', $character_class['level']);
  $db->insert($query);
}

function updateCharacterClass($character_class)
{
  GLOBAL $db;

  $query = new UpdateQuery('character_class');
  $query->addField('subclass_id', $character_class['subclass_id']);
  $query->addField('level', $character_class['level']);
  $query->addConditionSimple('character_id', $character_class['character_id']);
  $query->addConditionSimple('class_id', $character_class['class_id']);
  $db->update($query);
}

function deleteCharacterClass($character_class)
{
  GLOBAL $db;

  $query = new DeleteQuery('character_class');
  $query->addConditionSimple('character_id', $character_class['character_id']);
  $query->addConditionSimple('class_id', $character_class['class_id']);
  $db->delete($query);
}
