<?php

function installAttribute()
{
  GLOBAL $db;

  $query = new CreateQuery('attributes');
  $query->addField('id', 'INTEGER', 0, array('P', 'A'));
  $query->addField('code', 'TEXT', 8, array('N'));
  $query->addField('name', 'TEXT', 32, array('N'));
  $query->addField('description', 'TEXT', 1024);
  $db->create($query);

  $attributes = array(
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

  foreach($attributes as $attribute)
  {
    createAttribute($attribute);
  }
}

function getAttributePager($page = 1)
{
  GLOBAL $db;

  $query = new SelectQuery('attributes');
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

function getAttributeList()
{
  GLOBAL $db;

  $query = new SelectQuery('attributes');
  $query->addField('id')->addField('name', 'value');

  return $db->selectList($query);
}

function getAttributeCodeList()
{
  GLOBAL $db;

  $query = new SelectQuery('attributes');
  $query->addField('id')->addField('code', 'value');

  return $db->selectList($query);
}

function getAttribute($id)
{
  GLOBAL $db;

  $query = new SelectQuery('attributes');
  $query->addField('id');
  $query->addField('name');
  $query->addField('code');
  $query->addField('description');
  $query->addConditionSimple('id', $id);

  return $db->selectObject($query);
}

function createAttribute($attribute)
{
  GLOBAL $db;

  $query = new InsertQuery('attributes');
  $query->addField('name', $attribute['name']);
  $query->addField('code', $attribute['code']);
  $query->addField('description', $attribute['description']);

  return $db->insert($query);
}

function updateAttribute($attribute)
{
  GLOBAL $db;

  $query = new UpdateQuery('attributes');
  $query->addField('name', $attribute['name']);
  $query->addField('code', $attribute['code']);
  $query->addField('description', $attribute['description']);
  $query->addConditionSimple('id', $attribute['id']);

  $db->update($query);
}

function deleteAttribute($id)
{
  GLOBAL $db;

  $query = new DeleteQuery('attributes');
  $query->addConditionSimple('id', $id);

  $db->delete($query);
}
