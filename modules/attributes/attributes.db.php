<?php

/******************************************************************************
 *
 *  Install.
 *
 ******************************************************************************/

function installAttributes()
{
  GLOBAL $db;

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
}

/******************************************************************************
 *
 *  Attributes.
 *
 ******************************************************************************/
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

function getAttribute($id)
{
  GLOBAL $db;

  $query = new SelectQuery('attributes');
  $query->addField('id');
  $query->addField('name');
  $query->addField('code');
  $query->addField('description');
  $query->addConditionSimple('id', $id);

  $results = $db->select($query);
  if (!$results)
  {
    return FALSE;
  }
  $result = array_shift($results);
  return $result;
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

/******************************************************************************
 *
 *  Skills.
 *
 ******************************************************************************/
function getSkillPager($page = 1)
{
  GLOBAL $db;

  $query = new SelectQuery('skills');
  $query->addField('id');
  $query->addField('name');
  $query->addField('code');
  $query->addField('description');
  $query->addField('attribute_id');
  $query->addPager($page);

  $results = $db->select($query);

  if (!$results)
  {
    return array();
  }
  return $results;
}

function getSkillList()
{
  GLOBAL $db;

  $query = new SelectQuery('skills');
  $query->addField('id')->addField('name', 'value');

  return $db->selectList($query);
}

function getSkill($id)
{
  GLOBAL $db;

  $query = new SelectQuery('skills');
  $query->addField('id');
  $query->addField('name');
  $query->addField('code');
  $query->addField('description');
  $query->addField('attribute_id');
  $query->addConditionSimple('id', $id);

  $results = $db->select($query);
  if (!$results)
  {
    return FALSE;
  }
  $result = array_shift($results);
  return $result;
}

function createSkill($skill)
{
  GLOBAL $db;

  $query = new InsertQuery('skills');
  $query->addField('name', $skill['name']);
  $query->addField('code', $skill['code']);
  $query->addField('description', $skill['description']);
  $query->addField('attribute_id', $skill['attribute_id']);

  return $db->insert($query);
}

function updateSkill($skill)
{
  GLOBAL $db;

  $query = new UpdateQuery('skills');
  $query->addField('name', $skill['name']);
  $query->addField('code', $skill['code']);
  $query->addField('description', $skill['description']);
  $query->addField('attribute_id', $skill['attribute_id']);
  $query->addConditionSimple('id', $skill['id']);

  $db->update($query);
}

function deleteSkill($id)
{
  GLOBAL $db;

  $query = new DeleteQuery('skills');
  $query->addConditionSimple('id', $id);
  $db->delete($query);
}
