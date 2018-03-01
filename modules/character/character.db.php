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

  $query = new CreateQuery('attributes');
  $query->addField('id', 'INTEGER', array('P', 'A'));
  $query->addField('code', 'TEXT', array('N'));
  $query->addField('name', 'TEXT', array('N'));
  $query->addField('description', 'TEXT');
  $db->create($query);

  $query = new CreateQuery('skills');
  $query->addField('id', 'INTEGER', array('P', 'A'));
  $query->addField('code', 'TEXT', array('N'));
  $query->addField('name', 'TEXT', array('N'));
  $query->addField('attribute_id', 'INTEGER', array('N'));
  $query->addField('description', 'TEXT');
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

  $query = new CreateQuery('character_class');
  $query->addField('character_id', 'INTEGER', array('P', 'N'));
  $query->addField('class_id', 'INTEGER', array('P', 'N'));
  $query->addField('subclass_id', 'INTEGER', array('N'));
  $query->addField('level', 'INTEGER', array('N'), 0);
  $db->create($query);

  $query = new CreateQuery('character_attribute');
  $query->addField('character_id', 'INTEGER', array('P', 'N'));
  $query->addField('attribute_id', 'INTEGER', array('P', 'N'));
  $query->addField('score', 'INTEGER', array('N'), 0);
  $query->addField('modifier', 'INTEGER', array('N'), 0);
  $query->addField('st_pb', 'INTEGER', array('N'), 0);
  $query->addField('st', 'INTEGER', array('N'), 0);
  $db->create($query);

  $query = new CreateQuery('character_skill');
  $query->addField('character_id', 'INTEGER', array('P', 'N'));
  $query->addField('skill_id', 'INTEGER', array('P', 'N'));
  $query->addField('pb', 'INTEGER', array('N'), 0);
  $query->addField('modifier', 'INTEGER', array('N'), 0);
  $query->addField('add', 'INTEGER', array('N'), 0);
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

  $query = new SelectQuery('characters');
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
  $query->addCondition('id');
  $args = array(':id' => $id);
  $results = $db->select($query, $args);

  if (!$results)
  {
    return FALSE;
  }
  $result = array_shift($results);
  return $result;
}

/******************************************************************************
 *
 *  Character Class.
 *
 ******************************************************************************/

function getCharacterClasses($character_id)
{
  GLOBAL $db;

  $query = new SelectQuery('character_class');
  $query->addField('class_id');
  $query->addField('subclass_id');
  $query->addField('level');
  $query->addCondition('character_id');
  $query->addOrder('level', 'DESC');
  $args = array(':character_id' => $character_id);
  $results = $db->select($query, $args);

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
  $query->addCondition('character_id');
  $query->addCondition('class_id');
  $args = array(
    ':character_id' => $character_id,
    ':class_id' => $class_id,
  );
  $results = $db->select($query, $args);

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
  $query->addField('character_id');
  $query->addField('class_id');
  $query->addField('subclass_id');
  $query->addField('level');
  $db->insert($query, SQLite::buildArgs($character_class));
}

function updateCharacterClass($character_class)
{
  GLOBAL $db;

  $query = new UpdateQuery('character_class');
  $query->addField('subclass_id');
  $query->addField('level');
  $query->addCondition('character_id');
  $query->addCondition('class_id');
  $db->update($query, SQLite::buildArgs($character_class));
}

function deleteCharacterClass($character_class)
{
  GLOBAL $db;

  $query = new DeleteQuery('character_class');
  $query->addCondition('character_id');
  $query->addCondition('class_id');
  $args = array(
    'character_id' => $character_class['character_id'],
    'class_id' => $character_class['class_id'],
  );
  $db->delete($query, $args);
}

/******************************************************************************
 *
 *  Player.
 *
 ******************************************************************************/

function getPlayerList()
{
  GLOBAL $db;

  $query = new SelectQuery('players');
  $query->addField('id')->addField('name', 'value');

  return $db->selectList($query);
}
