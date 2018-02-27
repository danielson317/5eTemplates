<?php

/******************************************************************************
 *
 *  Character.
 *
 ******************************************************************************/
function installClass()
{
  GLOBAL $db;

  $query = new CreateQuery('classes');
  $query->addField('id', 'INTEGER', array('P', 'A'));
  $query->addField('name', 'TEXT', array('N'));
  $query->addField('hit_die', 'INTEGER', array('N'));
  $query->addField('description', 'TEXT');
  $db->create($query);

  $query = new CreateQuery('subclass');
  $query->addField('id', 'INTEGER', array('P', 'A'));
  $query->addField('name', 'TEXT', array('N'));
  $query->addField('class_id', 'INTEGER', array('N'));
  $query->addField('description', 'TEXT');
  $db->create($query);
}


/******************************************************************************
 *
 *  Lists.
 *
 ******************************************************************************/

function getClassList()
{
  GLOBAL $db;

  $query = new SelectQuery('classes');
  $query->addField('id')->addField('name', 'value');

  return $db->selectList($query);
}

function getSubclassList()
{
  GLOBAL $db;

  $query = new SelectQuery('subclasses');
  $query->addField('id')->addField('name', 'value');

  return $db->selectList($query);
}
