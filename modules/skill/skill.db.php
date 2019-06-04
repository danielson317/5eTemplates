<?php

function installSkill()
{
  GLOBAL $db;

  $query = new CreateQuery('skills');
  $query->addField('id', 'INTEGER', 0, array('P', 'A'));
  $query->addField('code', 'TEXT', 8, array('N'));
  $query->addField('name', 'TEXT', 32, array('N'));
  $query->addField('attribute_id', 'INTEGER', 0, array('N'));
  $query->addField('description', 'TEXT', 1024);
  $db->create($query);
}

function getSkillPager($page = 1)
{
  GLOBAL $db;

  $query = new SelectQuery('skills');
  $query->addField('id');
  $query->addField('name');
  $query->addField('code');
  $query->addField('description');
  $query->addField('attribute_id');
  $query->addOrderSimple('name');
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
  $query->addOrderSimple('name');

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

  return $db->selectObject($query);
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
