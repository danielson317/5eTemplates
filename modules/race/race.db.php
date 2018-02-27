<?php

/******************************************************************************
 *
 *  Character.
 *
 ******************************************************************************/
function installRace()
{
  GLOBAL $db;

  $query = new CreateQuery('races');
  $query->addField('id', 'INTEGER', array('P', 'A'));
  $query->addField('name', 'TEXT', array('N'));
  $query->addField('description', 'TEXT');
  $query->addField('speed', 'INTEGER', array('N'), 0);
  $db->create($query);
}

function getRaceList()
{
  GLOBAL $db;

  $query = new SelectQuery('races');
  $query->addField('id')->addField('name', 'value');

  return $db->selectList($query);
}
