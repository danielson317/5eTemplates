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

function createAttribute($attribute)
{
  GLOBAL $db;

  $query = new InsertQuery('attributes');
  $query->addField('name');
  $query->addField('code');
  $query->addField('description');
  $args = $db->buildArgs($attribute);

  return $db->insert($query, $args);
}

function updateAttribute($attribute)
{
  GLOBAL $db;

  $query = new UpdateQuery('attributes');
  $query->addField('name');
  $query->addField('code');
  $query->addField('description');
  $query->addCondition('id');
  $args = $db->buildArgs($attribute);

  $db->update($query, $args);
}

function deleteAttribute($id)
{
  GLOBAL $db;

  $query = new DeleteQuery('attributes');
  $query->addCondition('id');
  $args = array(':id' => $id);

  $db->delete($query, $args);
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

function createSkill($skill)
{
  GLOBAL $db;

  $query = new InsertQuery('skills');
  $query->addField('name');
  $query->addField('code');
  $query->addField('description');
  $query->addField('attribute_id');
  $args = $db->buildArgs($skill);

  return $db->insert($query, $args);
}

function updateSkill($skill)
{
  GLOBAL $db;

  $query = new UpdateQuery('skills');
  $query->addField('name');
  $query->addField('code');
  $query->addField('description');
  $query->addField('attribute_id');
  $query->addCondition('id');
  $args = $db->buildArgs($skill);

  $db->update($query, $args);
}

function deleteSkill($id)
{
  GLOBAL $db;

  $query = new DeleteQuery('skills');
  $query->addCondition('id');
  $args = array(':id' => $id);

  $db->delete($query, $args);
}
