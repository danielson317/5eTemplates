<?php

function installSpell()
{
  GLOBAL $db;

  // Spells.
  $query = new CreateQuery('spells');
  $query->addField('id', CreateQuery::TYPE_INTEGER, 0, array('P', 'A'));
  $query->addField('name', CreateQuery::TYPE_STRING, 32, array('N'));
  $query->addField('level', CreateQuery::TYPE_INTEGER, 0, array('N'), 0);
  $query->addField('school_id', CreateQuery::TYPE_INTEGER, 0, array('N'), 0);
  $query->addField('ritual', CreateQuery::TYPE_INTEGER, 0, array('N'), 0);
  $query->addField('speed', CreateQuery::TYPE_INTEGER, 0, array('N'), 0);
  $query->addField('range', CreateQuery::TYPE_INTEGER, 0, array('N'), 0);
  $query->addField('concentration', CreateQuery::TYPE_INTEGER, 0, array('N'), 0);
  $query->addField('verbal', CreateQuery::TYPE_INTEGER, 0, array('N'), 0);
  $query->addField('semantic', CreateQuery::TYPE_INTEGER, 0, array('N'), 0);
  $query->addField('material', CreateQuery::TYPE_STRING, 128);
  $query->addField('duration', CreateQuery::TYPE_INTEGER, 0, array('N'), 0);
  $query->addField('aoe_id', CreateQuery::TYPE_INTEGER, 0, array('N'), 0);
  $query->addField('aoe_range', CreateQuery::TYPE_INTEGER, 0, array('N'), 0);
  $query->addField('shorthand', CreateQuery::TYPE_STRING, 128);
  $query->addField('description', CreateQuery::TYPE_STRING, 1024);
  $query->addField('alternate', CreateQuery::TYPE_STRING, 1024);
  $query->addField('source_id', CreateQuery::TYPE_INTEGER, 0, array('N'), 0);
  $query->addField('source_location', CreateQuery::TYPE_INTEGER, 0, array('N'), 0);
  $db->create($query);
}

/**
 * @param int $page
 *
 * @return array|false
 */
function getSpellPager($page = 1)
{
  GLOBAL $db;

  $query = new SelectQuery('spells');
  $query->addField('id');
  $query->addField('name');
  $query->addField('school_id');
  $query->addField('level');
  $query->addField('shorthand');
//  $query->addField('description');
  $query->addOrderSimple('name');
  $query->addPager($page);

  return $db->select($query);
}

/**
 * @param int $spell_id
 *
 * @return array|false
 */
function getSpell($spell_id)
{
  GLOBAL $db;

  $query = new SelectQuery('spells');
  $query->addField('id');
  $query->addField('name');
  $query->addField('school_id');
  $query->addField('level');
  $query->addField('speed');
  $query->addField('range');
  $query->addField('ritual');
  $query->addField('concentration');
  $query->addField('verbal');
  $query->addField('semantic');
  $query->addField('material');
  $query->addField('duration');
  $query->addField('aoe_id');
  $query->addField('aoe_range');
  $query->addField('shorthand');
  $query->addField('description');
  $query->addField('alternate');
  $query->addField('source_id');
  $query->addField('source_location');
  $query->addConditionSimple('id', $spell_id);

  return $db->selectObject($query);
}

/**
 * @param array $spell
 *
 * @return int
 */
function createSpell($spell)
{
  GLOBAL $db;

  $query = new InsertQuery('spells');
  $query->addField('name', $spell['name']);
  $query->addField('school_id', $spell['school_id']);
  $query->addField('level', $spell['level']);
  $query->addField('speed', $spell['speed']);
  $query->addField('range', $spell['range']);
  $query->addField('ritual', $spell['ritual']);
  $query->addField('concentration', $spell['concentration']);
  $query->addField('verbal', $spell['verbal']);
  $query->addField('semantic', $spell['semantic']);
  $query->addField('material', $spell['material']);
  $query->addField('duration', $spell['duration']);
  $query->addField('aoe_id', $spell['aoe_id']);
  $query->addField('aoe_range', $spell['aoe_range']);
  $query->addField('shorthand', $spell['shorthand']);
  $query->addField('description', $spell['description']);
  $query->addField('alternate', $spell['alternate']);
  $query->addField('source_id', $spell['source_id']);
  $query->addField('source_location', $spell['source_location']);

  return $db->insert($query);
}

/**
 * @param array $spell
 */
function updateSpell($spell)
{
  GLOBAL $db;

  $query = new UpdateQuery('spells');
  $query->addField('name', $spell['name']);
  $query->addField('school_id', $spell['school_id']);
  $query->addField('level', $spell['level']);
  $query->addField('speed', $spell['speed']);
  $query->addField('range', $spell['range']);
  $query->addField('ritual', $spell['ritual']);
  $query->addField('concentration', $spell['concentration']);
  $query->addField('verbal', $spell['verbal']);
  $query->addField('semantic', $spell['semantic']);
  $query->addField('material', $spell['material']);
  $query->addField('duration', $spell['duration']);
  $query->addField('aoe_id', $spell['aoe_id']);
  $query->addField('aoe_range', $spell['aoe_range']);
  $query->addField('shorthand', $spell['shorthand']);
  $query->addField('description', $spell['description']);
  $query->addField('alternate', $spell['alternate']);
  $query->addField('source_id', $spell['source_id']);
  $query->addField('source_location', $spell['source_location']);
  $query->addConditionSimple('id', $spell['id']);

  $db->update($query);
}

/**
 * @param int $spell_id
 */
function deleteSpell($spell_id)
{
  GLOBAL $db;

  $query = new DeleteQuery('spells');
  $query->addConditionSimple('id', $spell_id);
  $db->delete($query);
}
