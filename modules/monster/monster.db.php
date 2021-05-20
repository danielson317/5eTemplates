<?php

/******************************************************************************
 *
 *  Install.
 *
 ******************************************************************************/
function installMonster()
{
  GLOBAL $db;

  // Trait, action, reaction, legendary action, etc.
  $query = new CreateQuery('action_types');
  $query->addField('id', CreateQuery::TYPE_INTEGER, 0, array('P', 'A'));
  $query->addField('code', CreateQuery::TYPE_STRING, 8, array('N'));
  $query->addField('description', CreateQuery::TYPE_STRING, 1024);
  $db->create($query);

  // 0, 1/8, 1/4, 1/2, 1, 2, etc.
  $query = new CreateQuery('challenge_ratings');
  $query->addField('id', CreateQuery::TYPE_INTEGER, 0, array('P', 'A'));
  $query->addField('code', CreateQuery::TYPE_STRING, 8, array('N'));
  $query->addField('xp', CreateQuery::TYPE_INTEGER, 0, array('N'));
  $db->create($query);

  // Prone, poisoned, exhaustion 1, etc.
  $query = new CreateQuery('conditions');
  $query->addField('id', CreateQuery::TYPE_INTEGER, 0, array('P', 'A'));
  $query->addField('code', CreateQuery::TYPE_STRING, 8, array('N'));
  $query->addField('description', CreateQuery::TYPE_STRING, 1024);
  $db->create($query);

  // Battle-cry, great-axe, spell-casting, etc.
  $query = new CreateQuery('monster_actions');
  $query->addField('monster_id', CreateQuery::TYPE_INTEGER, 0, array('N'));
  $query->addField('action_type_id', CreateQuery::TYPE_INTEGER, 0, array('N'));
  $query->addField('name', CreateQuery::TYPE_STRING, 32, array('N'));
  $query->addField('description', CreateQuery::TYPE_STRING, 1024);
  $db->create($query);

  // Constitution, Dexterity, Strength, etc.
  $query = new CreateQuery('monster_abilities');
  $query->addField('monster_id', CreateQuery::TYPE_INTEGER, 0, array('P', 'N'));
  $query->addField('ability_id', CreateQuery::TYPE_INTEGER, 0, array('P', 'N'));
  $query->addField('score', CreateQuery::TYPE_INTEGER, 0, array('N'), 8);
  $query->addField('modifier', CreateQuery::TYPE_INTEGER, 0, array('N'), -1);
  $query->addField('proficiency', CreateQuery::TYPE_INTEGER, 0, array('N'), 0);
  $query->addField('saving_throw', CreateQuery::TYPE_DECIMAL, 0, array('N'), 0);
  $db->create($query);

  // Immune to charmed, etc. 0 = immune, 0.5 = resistant, 1 = normal, 2 = vulnerable
  $query = new CreateQuery('monster_conditions');
  $query->addField('monster_id', CreateQuery::TYPE_INTEGER, 0, array('P', 'N'));
  $query->addField('condition_id', CreateQuery::TYPE_INTEGER, 0, array('P', 'N'));
  $query->addField('modifier', CreateQuery::TYPE_DECIMAL, 0, array('N'), 1);
  $db->create($query);

  // Immune to poison, vulnerable to bludgeoning, resistant to piercing, etc. 0 = immune, 0.5 = resistant, 1 = normal, 2 = vulnerable
  $query = new CreateQuery('monster_damage_types');
  $query->addField('monster_id', CreateQuery::TYPE_INTEGER, 0, array('P', 'N'));
  $query->addField('damage_type_id', CreateQuery::TYPE_INTEGER, 0, array('P', 'N'));
  $query->addField('modifier', CreateQuery::TYPE_DECIMAL, 0, array('N'), 1);
  $db->create($query);

  // Common, Dwarfish, Orc.
  $query = new CreateQuery('monster_conditions');
  $query->addField('monster_id', CreateQuery::TYPE_INTEGER, 0, array('P', 'N'));
  $query->addField('language_id', CreateQuery::TYPE_INTEGER, 0, array('P', 'N'));
  $db->create($query);

  // Darkvision, blindsight. etc.
  $query = new CreateQuery('monster_senses');
  $query->addField('monster_id', CreateQuery::TYPE_INTEGER, 0, array('P', 'A'));
  $query->addField('sense_id', CreateQuery::TYPE_INTEGER, 0, array('P', 'N'));
  $query->addField('range', CreateQuery::TYPE_INTEGER, 0, array('N'), 0);
  $db->create($query);

  // Perception, religion, stealth, etc.
  $query = new CreateQuery('monster_skills');
  $query->addField('monster_id', CreateQuery::TYPE_INTEGER, 0, array('P', 'A'));
  $query->addField('skill_id', CreateQuery::TYPE_INTEGER, 0, array('P', 'N'));
  $query->addField('proficiency', CreateQuery::TYPE_INTEGER, 0, array('N'), 0);
  $query->addField('modifier', CreateQuery::TYPE_INTEGER, 0, array('N'), 0);
  $db->create($query);

  // Beasts, humanoids, undead, etc.
  $query = new CreateQuery('monster_types');
  $query->addField('id', CreateQuery::TYPE_INTEGER, 0, array('P', 'A'));
  $query->addField('name', CreateQuery::TYPE_STRING, 32, array('N'));
  $query->addField('description', CreateQuery::TYPE_STRING, 1024);
  $db->create($query);

  // Dragons, and orcs, and bears, oh my!
  $query = new CreateQuery('monsters');
  $query->addField('id', CreateQuery::TYPE_INTEGER, 0, array('P', 'A'));
  $query->addField('name', CreateQuery::TYPE_STRING, 32, array('N'));
  $query->addField('size', CreateQuery::TYPE_STRING, 8);
  $query->addField('monster_type_id', CreateQuery::TYPE_INTEGER, 0, array('N'), 0);
  $query->addField('type_tag', CreateQuery::TYPE_STRING, 32);
  $query->addField('alignment', CreateQuery::TYPE_STRING, 8);
  $query->addField('ac', CreateQuery::TYPE_INTEGER);
  $query->addField('ac_details', CreateQuery::TYPE_STRING, 1024);
  $query->addField('hit_die_count', CreateQuery::TYPE_INTEGER, 0, array('N'), 0);
  $query->addField('hit_die', CreateQuery::TYPE_INTEGER, 0, array('N'), 0);
  $query->addField('hp_bonus', CreateQuery::TYPE_INTEGER, 0, array('N'), 0);
  $query->addField('speed', CreateQuery::TYPE_INTEGER, 0, array('N'), 0);
  $query->addField('fly', CreateQuery::TYPE_INTEGER, 0, array('N'), 0);
  $query->addField('swim', CreateQuery::TYPE_INTEGER, 0, array('N'), 0);
  $query->addField('challenge_rating_id', CreateQuery::TYPE_INTEGER, 0, array('N'));
  $query->addField('description', CreateQuery::TYPE_STRING, 1024);
  $query->addField('source_id', CreateQuery::TYPE_INTEGER, 0, array('N'), 0);
  $query->addField('source_location', CreateQuery::TYPE_INTEGER, 0, array('N'), 0);
  $db->create($query);

  // Darkvision, blindsight. etc.
  $query = new CreateQuery('senses');
  $query->addField('id', CreateQuery::TYPE_INTEGER, 0, array('P', 'A'));
  $query->addField('name', CreateQuery::TYPE_STRING, 32, array('N'));
  $query->addField('description', CreateQuery::TYPE_STRING, 1024);
  $db->create($query);

}

/******************************************************************************
 *
 *  Monster.
 *
 ******************************************************************************/

/**
 * @param int $page
 *
 * @return array|bool
 */
function getMonsterPager($page = 1)
{
  GLOBAL $db;

  $query = new SelectQuery('monsters');
  $query->addField('id');
  $query->addField('name');
  $query->addField('monster_type_id');
  $query->addField('value');
  $query->addField('magic');
  $query->addField('attunement');
  $query->addField('description');
  $query->addOrderSimple('id');
  $query->addPager($page);

  return $db->select($query);
}

/**
 * @param int $monster_id
 *
 * @return bool|mixed
 */
function getMonster($monster_id)
{
  GLOBAL $db;

  $query = new SelectQuery('monsters');
  $query->addField('id');
  $query->addField('name');
  $query->addField('monster_type_id');
  $query->addField('value');
  $query->addField('magic');
  $query->addField('attunement');
  $query->addField('description');
  $query->addConditionSimple('id', $monster_id);
  return $db->selectObject($query);
}

/**
 * @param array $monster
 *
 * @return int
 */
function createMonster($monster)
{
  GLOBAL $db;

  $query = new InsertQuery('monsters');
  $query->addField('name', $monster['name']);
  $query->addField('monster_type_id', $monster['monster_type_id']);
  $query->addField('value', $monster['value']);
  $query->addField('magic', $monster['magic']);
  $query->addField('attunement', $monster['attunement']);
  $query->addField('description', $monster['description']);

  return $db->insert($query);
}

/**
 * @param $monster
 */
function updateMonster($monster)
{
  GLOBAL $db;

  $query = new UpdateQuery('monsters');
  $query->addField('name', $monster['name']);
  $query->addField('monster_type_id', $monster['monster_type_id']);
  $query->addField('value', $monster['value']);
  $query->addField('magic', $monster['magic']);
  $query->addField('attunement', $monster['attunement']);
  $query->addField('description', $monster['description']);
  $query->addConditionSimple('id', $monster['id']);

  $db->update($query);
}

function deleteMonster($id)
{

}

/******************************************************************************
 *
 *  Monster Type.
 *
 ******************************************************************************/

function getMonsterTypeList()
{
  GLOBAL $db;

  $query = new SelectQuery('monster_types');
  $query->addField('id')->addField('name', 'value');

  return $db->selectList($query);
}
