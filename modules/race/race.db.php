<?php

/******************************************************************************
 *
 *  Character.
 *
 ******************************************************************************/
function installRace()
{
  GLOBAL $db;

  $query = new CreateQuery('race');
  $query->addField('id', 'INTEGER', array('P', 'A'));
  $query->addField('name', 'TEXT', array('N'));
  $query->addField('description', 'TEXT');
  $query->addField('speed', 'INTEGER', array('N'), 0);
  $db->create($query);
}
