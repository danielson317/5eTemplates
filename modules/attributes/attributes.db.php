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

function getAttributeList()
{
  GLOBAL $db;

  $query = new SelectQuery('attributes');
  $query->addField('id')->addField('name', 'value');

  return $db->selectList($query);
}

/******************************************************************************
 *
 *  Skills.
 *
 ******************************************************************************/

function getSkillList()
{
  GLOBAL $db;

  $query = new SelectQuery('skills');
  $query->addField('id')->addField('name', 'value');

  return $db->selectList($query);
}
