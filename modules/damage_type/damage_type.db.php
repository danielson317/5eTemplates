<?php

/**
 *
 */
function installDamageType()
{
  GLOBAL $db;

  // Bludgeoning, Force, Psychic, etc.
  $query = new CreateQuery('damage_types');
  $query->addField('id', CreateQuery::TYPE_INTEGER, 0, array('P', 'A'));
  $query->addField('code', CreateQuery::TYPE_STRING, 8, array('N'));
  $query->addField('name', CreateQuery::TYPE_STRING, 32, array('N'));
  $query->addField('description', CreateQuery::TYPE_STRING, 1024);
  $db->create($query);

  $damage_types = array(
    array(
      'code' => 'Acid',
      'name' => 'Acid',
      'description' => '',
    ),
    array(
      'code' => 'Bl',
      'name' => 'Bludgeoning',
      'description' => '',
    ),
    array(
      'code' => 'Cold',
      'name' => 'Cold',
      'description' => '',
    ),
    array(
      'code' => 'Fire',
      'name' => 'Fire',
      'description' => '',
    ),
    array(
      'code' => 'Frc',
      'name' => 'Force',
      'description' => '',
    ),
    array(
      'code' => 'Ltn',
      'name' => 'Lightning',
      'description' => '',
    ),
    array(
      'code' => 'Ncr',
      'name' => 'Necrotic',
      'description' => '',
    ),
    array(
      'code' => 'Pi',
      'name' => 'Piercing',
      'description' => '',
    ),
    array(
      'code' => 'Poi',
      'name' => 'Poison',
      'description' => '',
    ),
    array(
      'code' => 'Psy',
      'name' => 'Psychic',
      'description' => '',
    ),
    array(
      'code' => 'Rad',
      'name' => 'Radiant',
      'description' => '',
    ),
    array(
      'code' => 'Sl',
      'name' => 'Slashing',
      'description' => '',
    ),
    array(
      'code' => 'Thnd',
      'name' => 'Thunder',
      'description' => '',
    ),
  );

  foreach ($damage_types as $damage_type)
  {
    createDamageType($damage_type);
  }
}

/**
 * @param int $page
 *
 * @return array|false
 */
function getDamageTypePager($page = 1)
{
  GLOBAL $db;

  $query = new SelectQuery('damage_types');
  $query->addField('id');
  $query->addField('name');
  $query->addField('code');
  $query->addField('description');
  $query->addOrderSimple('name');
  $query->addPager($page);

  return $db->select($query);
}

/**
 * @return array
 */
function getDamageTypeList()
{
  GLOBAL $db;

  $query = new SelectQuery('damage_types');
  $query->addField('id')->addField('name', 'value');

  return $db->selectList($query);
}

/**
 * @return array
 */
function getDamageTypeCodeList()
{
  GLOBAL $db;

  $query = new SelectQuery('damage_types');
  $query->addField('id')->addField('code', 'value');

  return $db->selectList($query);
}

/**
 * @param int $damage_type_id
 *
 * @return array|false
 */
function getDamageType($damage_type_id)
{
  GLOBAL $db;

  $query = new SelectQuery('damage_types');
  $query->addField('id');
  $query->addField('name');
  $query->addField('code');
  $query->addField('description');
  $query->addConditionSimple('id', $damage_type_id);
  return $db->selectObject($query);
}

/**
 * @param array $damage_type
 *
 * @return int
 */
function createDamageType($damage_type)
{
  GLOBAL $db;

  $query = new InsertQuery('damage_types');
  $query->addField('name', $damage_type['name']);
  $query->addField('code', $damage_type['code']);
  $query->addField('description', $damage_type['description']);

  return $db->insert($query);
}

/**
 * @param array $damage_type
 */
function updateDamageType($damage_type)
{
  GLOBAL $db;

  $query = new UpdateQuery('damage_types');
  $query->addField('name', $damage_type['name']);
  $query->addField('code', $damage_type['code']);
  $query->addField('description', $damage_type['description']);
  $query->addConditionSimple('id', $damage_type['id']);

  $db->update($query);
}

/**
 * @param int $damage_type_id
 */
function deleteDamageType($damage_type_id)
{
  GLOBAL $db;
  $query = new DeleteQuery('damage_types');
  $query->addConditionSimple('id', $damage_type_id);

  $db->delete($query);
}

