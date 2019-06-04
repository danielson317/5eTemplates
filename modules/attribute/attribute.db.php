<?php

function installAttribute()
{
  GLOBAL $db;

  $query = new CreateQuery('attribute');
  $query->addField('id', 'INTEGER', 0, array('P', 'A'));
  $query->addField('code', 'TEXT', 8, array('N'));
  $query->addField('name', 'TEXT', 32, array('N'));
  $query->addField('description', 'TEXT', 1024);
  $db->create($query);

  $query = new CreateQuery('skills');
  $query->addField('id', 'INTEGER', 0, array('P', 'A'));
  $query->addField('code', 'TEXT', 8, array('N'));
  $query->addField('name', 'TEXT', 32, array('N'));
  $query->addField('attribute_id', 'INTEGER', 0, array('N'));
  $query->addField('description', 'TEXT', 1024);
  $db->create($query);
}

function getAttributePager($page = 1)
{
  GLOBAL $db;

  $query = new SelectQuery('attribute');
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

  $query = new SelectQuery('attribute');
  $query->addField('id')->addField('name', 'value');

  return $db->selectList($query);
}

function getAttribute($id)
{
  GLOBAL $db;

  $query = new SelectQuery('attribute');
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

  $query = new InsertQuery('attribute');
  $query->addField('name', $attribute['name']);
  $query->addField('code', $attribute['code']);
  $query->addField('description', $attribute['description']);

  return $db->insert($query);
}

function updateAttribute($attribute)
{
  GLOBAL $db;

  $query = new UpdateQuery('attribute');
  $query->addField('name', $attribute['name']);
  $query->addField('code', $attribute['code']);
  $query->addField('description', $attribute['description']);
  $query->addConditionSimple('id', $attribute['id']);

  $db->update($query);
}

function deleteAttribute($id)
{
  GLOBAL $db;

  $query = new DeleteQuery('attribute');
  $query->addConditionSimple('id', $id);

  $db->delete($query);
}
