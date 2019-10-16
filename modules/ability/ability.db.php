<?php

function installAbility()
{
  GLOBAL $db;

  $query = new CreateQuery('abilities');
  $query->addField('id', 'INTEGER', 0, array('P', 'A'));
  $query->addField('code', 'TEXT', 8, array('N'));
  $query->addField('name', 'TEXT', 32, array('N'));
  $query->addField('description', 'TEXT', 1024);
  $db->create($query);

  $abilities = array(
    array(
      'code' => 'STR',
      'name' => 'Strength',
      'description' => 'Raw physical strength. The ability to push/pull or overpower.',
    ),
    array(
      'code' => 'DEX',
      'name' => 'Dexterity',
      'description' => 'Speed, reaction time, balance, and overall control of your body.',
    ),
    array(
      'code' => 'CON',
      'name' => 'Constitution',
      'description' => 'Fortitude, resistance and the ability to withstand and survive damage.',
    ),
    array(
      'code' => 'WIS',
      'name' => 'Wisdom',
      'description' => 'The ability to see past the surface and infer a solution.',
    ),
    array(
      'code' => 'INT',
      'name' => 'Intelligence',
      'description' => 'The ability to process information and form logical conclusions.',
    ),
    array(
      'code' => 'CHR',
      'name' => 'Charisma',
      'description' => 'The ability to communicate with people and persuade, manipulate, or impress them.',
    ),
  );

  foreach($abilities as $ability)
  {
    createAbility($ability);
  }
}

function getAbilityPager($page = 1)
{
  GLOBAL $db;

  $query = new SelectQuery('abilities');
  $query->addField('id');
  $query->addField('name');
  $query->addField('code');
  $query->addField('description');
  $query->addPager($page);

  $results = $db->select($query);

  if (!$results)
  {
    return array();
  }
  return $results;
}

function getAbilityList()
{
  GLOBAL $db;

  $query = new SelectQuery('abilities');
  $query->addField('id')->addField('name', 'value');

  return $db->selectList($query);
}

function getAbilityCodeList()
{
  GLOBAL $db;

  $query = new SelectQuery('abilities');
  $query->addField('id')->addField('code', 'value');

  return $db->selectList($query);
}

function getAbility($id)
{
  GLOBAL $db;

  $query = new SelectQuery('abilities');
  $query->addField('id');
  $query->addField('name');
  $query->addField('code');
  $query->addField('description');
  $query->addConditionSimple('id', $id);

  return $db->selectObject($query);
}

function createAbility($ability)
{
  GLOBAL $db;

  $query = new InsertQuery('abilities');
  $query->addField('name', $ability['name']);
  $query->addField('code', $ability['code']);
  $query->addField('description', $ability['description']);

  return $db->insert($query);
}

function updateAbility($ability)
{
  GLOBAL $db;

  $query = new UpdateQuery('abilities');
  $query->addField('name', $ability['name']);
  $query->addField('code', $ability['code']);
  $query->addField('description', $ability['description']);
  $query->addConditionSimple('id', $ability['id']);

  $db->update($query);
}

function deleteAbility($id)
{
  GLOBAL $db;

  $query = new DeleteQuery('abilities');
  $query->addConditionSimple('id', $id);

  $db->delete($query);
}
