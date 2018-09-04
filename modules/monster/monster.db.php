<?php

/******************************************************************************
 *
 *  Monster.
 *
 ******************************************************************************/
function installMonster()
{
  GLOBAL $db;

  // Trait, action, reaction, legendary action, etc.
  $query = new CreateQuery('action_types');
  $query->addField('id', 'INTEGER', array('P', 'A'));
  $query->addField('code', 'TEXT', array('N'));
  $query->addField('description', 'TEXT');
  $db->create($query);

  // 0, 1/8, 1/4, 1/2, 1, 2, etc.
  $query = new CreateQuery('challenge_ratings');
  $query->addField('id', 'INTEGER', array('P', 'A'));
  $query->addField('code', 'TEXT', array('N'));
  $query->addField('xp', 'INTEGER', array('N'));
  $db->create($query);

  // Prone, poisoned, exhaustion 1, etc.
  $query = new CreateQuery('conditions');
  $query->addField('id', 'INTEGER', array('P', 'A'));
  $query->addField('code', 'TEXT', array('N'));
  $query->addField('description', 'TEXT');
  $db->create($query);

  // Battle-cry, great-axe, spell-casting, etc.
  $query = new CreateQuery('monster_actions');
  $query->addField('monster_id', 'INTEGER', array('N'));
  $query->addField('action_type_id', 'INTEGER', array('N'));
  $query->addField('name', 'TEXT', array('N'));
  $query->addField('description', 'TEXT');
  $db->create($query);

  // Constitution, Dexterity, Strength, etc.
  $query = new CreateQuery('monster_attributes');
  $query->addField('monster_id', 'INTEGER', array('P', 'N'));
  $query->addField('attribute_id', 'INTEGER', array('P', 'N'));
  $query->addField('score', 'INTEGER', array('N'), 8);
  $query->addField('modifier', 'INTEGER', array('N'), -1);
  $query->addField('proficiency', 'INTEGER', array('N'), 0);
  $query->addField('saving_throw', 'real', array('N'), 0);
  $db->create($query);

  // Immune to charmed, etc. 0 = immune, 0.5 = resistant, 1 = normal, 2 = vulnerable
  $query = new CreateQuery('monster_conditions');
  $query->addField('monster_id', 'INTEGER', array('P', 'N'));
  $query->addField('condition_id', 'INTEGER', array('P', 'N'));
  $query->addField('modifier', 'REAL', array('N'), 1);
  $db->create($query);

  // Immune to poison, vulnerable to bludgeoning, resistant to piercing, etc. 0 = immune, 0.5 = resistant, 1 = normal, 2 = vulnerable
  $query = new CreateQuery('monster_damage_types');
  $query->addField('monster_id', 'INTEGER', array('P', 'N'));
  $query->addField('damage_type_id', 'INTEGER', array('P', 'N'));
  $query->addField('modifier', 'REAL', array('N'), 1);
  $db->create($query);

  // Common, Dwarfish, Orc.
  $query = new CreateQuery('monster_conditions');
  $query->addField('monster_id', 'INTEGER', array('P', 'N'));
  $query->addField('language_id', 'INTEGER', array('P', 'N'));
  $db->create($query);

  // Darkvision, blindsight. etc.
  $query = new CreateQuery('monster_senses');
  $query->addField('monster_id', 'INTEGER', array('P', 'A'));
  $query->addField('sense_id', 'INTEGER', array('P', 'N'));
  $query->addField('range', 'INTEGER', array('N'), 0);
  $db->create($query);

  // Perception, religion, stealth, etc.
  $query = new CreateQuery('monster_skills');
  $query->addField('monster_id', 'INTEGER', array('P', 'A'));
  $query->addField('skill_id', 'INTEGER', array('P', 'N'));
  $query->addField('proficiency', 'INTEGER', array('N'), 0);
  $query->addField('modifier', 'INTEGER', array('N'), 0);
  $db->create($query);

  // Beasts, humanoids, undead, etc.
  $query = new CreateQuery('monster_types');
  $query->addField('id', 'INTEGER', array('P', 'A'));
  $query->addField('name', 'TEXT', array('N'));
  $query->addField('description', 'TEXT');
  $db->create($query);

  // Dragons, and orcs, and bears, oh my!
  $query = new CreateQuery('monsters');
  $query->addField('id', 'INTEGER', array('P', 'A'));
  $query->addField('name', 'TEXT', array('N'));
  $query->addField('size', 'TEXT');
  $query->addField('monster_type_id', 'INTEGER', array('N'), 0);
  $query->addField('type_tag', 'TEXT');
  $query->addField('alignment', 'TEXT');
  $query->addField('ac', 'INTEGER');
  $query->addField('ac_details', 'TEXT');
  $query->addField('hit_die_count', 'INTEGER', array('N'), 0);
  $query->addField('hit_die', 'INTEGER', array('N'), 0);
  $query->addField('hp_bonus', 'INTEGER', array('N'), 0);
  $query->addField('speed', 'INTEGER', array('N'), 0);
  $query->addField('fly', 'INTEGER', array('N'), 0);
  $query->addField('swim', 'INTEGER', array('N'), 0);
  $query->addField('challenge_rating_id', 'INTEGER', array('N'));
  $query->addField('description', 'TEXT');
  $query->addField('source_id', 'INTEGER', array('N'), 0);
  $query->addField('source_location', 'INTEGER', array('N'), 0);
  $db->create($query);

  // Darkvision, blindsight. etc.
  $query = new CreateQuery('senses');
  $query->addField('id', 'INTEGER', array('P', 'A'));
  $query->addField('name', 'TEXT', array('N'));
  $query->addField('description', 'TEXT');
  $db->create($query);

}

function getMonsterPager($page = 1)
{
  GLOBAL $db;

  $query = new SelectQuery('monsters');
  $query->addField('id')
    ->addField('name')
    ->addField('monster_type_id')
    ->addField('value')
    ->addField('magic')
    ->addField('attunement')
    ->addField('description');
  $query->addOrder('id');
  //  $query->addOrder('monster_type_id');
  //  $query->addOrder('name');
  //  $query->addPager($page);

  $results = $db->select($query);
  if (!$results)
  {
    return array();
  }
  return $results;
}

function getMonster($id)
{
  GLOBAL $db;

  $query = new SelectQuery('monsters');
  $query->addField('id')
    ->addField('name')
    ->addField('monster_type_id')
    ->addField('value')
    ->addField('magic')
    ->addField('attunement')
    ->addField('description');
  $query->addConditionSimple('id', $id);
  $results = $db->select($query);
  if (!$results)
  {
    return FALSE;
  }
  $result = array_shift($results);
  return $result;
}

function createMonster($monster)
{
  GLOBAL $db;

  $query = new InsertQuery('monsters');
  $query->addField('name', $monster['name'])
    ->addField('monster_type_id', $monster['monster_type_id'])
    ->addField('value', $monster['value'])
    ->addField('magic', $monster['magic'])
    ->addField('attunement', $monster['attunement'])
    ->addField('description', $monster['description']);

  return $db->insert($query);
}

function updateMonster($monster)
{
  GLOBAL $db;

  $query = new UpdateQuery('monsters');
  $query->addField('name', $monster['name'])
    ->addField('monster_type_id', $monster['monster_type_id'])
    ->addField('value', $monster['value'])
    ->addField('magic', $monster['magic'])
    ->addField('attunement', $monster['attunement'])
    ->addField('description', $monster['description']);
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
