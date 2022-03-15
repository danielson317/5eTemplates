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
  $query->addField('id', CreateQuery::TYPE_INTEGER, 0, array('P', 'A'));
  $query->addField('name', CreateQuery::TYPE_STRING, 32, array('N'));
  $query->addField('xp', CreateQuery::TYPE_INTEGER, 0, array('N'), 0);
  $query->addField('race_id', CreateQuery::TYPE_INTEGER, 0, array('N'));
  $query->addField('subrace_id', CreateQuery::TYPE_INTEGER, 0);
  $query->addField('gender', CreateQuery::TYPE_STRING, 1);
  $query->addField('alignment', CreateQuery::TYPE_STRING, 8, array('N'));
  $query->addField('speed', CreateQuery::TYPE_INTEGER, 0, array('N'), 30);
  $query->addField('hp', CreateQuery::TYPE_INTEGER, 0, array('N'), 0);
  $query->addField('player_id', CreateQuery::TYPE_INTEGER);
  $query->addField('background_id', CreateQuery::TYPE_INTEGER, 0, array('N'), 0);
  $query->addField('personality', CreateQuery::TYPE_STRING);
  $query->addField('ideals', CreateQuery::TYPE_STRING);
  $query->addField('bonds', CreateQuery::TYPE_STRING);
  $query->addField('flaws', CreateQuery::TYPE_STRING);
  $query->addField('features', CreateQuery::TYPE_STRING);
  $db->create($query);

  $query = new CreateQuery('character_item_proficiency');
  $query->addField('character_id', CreateQuery::TYPE_INTEGER, 0, array('P', 'N'));
  $query->addField('item_id', CreateQuery::TYPE_INTEGER, 0, array('P', 'N'));
  $query->addField('proficiency_multiplier', CreateQuery::TYPE_DECIMAL, 0, array('N'), 0);
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
  $query->addField('gender');
  $query->addField('player_id');
  $query->addField('background_id');
  $query->addField('xp');
  $query->addField('alignment');
  $query->addField('hp');
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

function createCharacterSimple($character)
{
  GLOBAL $db;

  $query = new InsertQuery('characters');
  $query->addField('name', $character['name']);
  $query->addField('race_id', $character['race_id']);
  $query->addField('subrace_id', $character['subrace_id']);
  $query->addField('gender', $character['gender']);
  $query->addField('player_id', $character['player_id']);
  $query->addField('background_id', $character['background_id']);
  $query->addField('alignment', $character['alignment']);
  $query->addField('xp', 0);
  $query->addField('hp', 0);

  return $db->insert($query);
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
  $query->addField('speed', $character['speed']);
  $query->addField('personality', $character['personality']);
  $query->addField('ideals', $character['ideals']);
  $query->addField('bonds', $character['bonds']);
  $query->addField('flaws', $character['flaws']);
  $query->addField('features', $character['features']);

  $query->addConditionSimple('id', $character['id']);
  $db->update($query);
}
