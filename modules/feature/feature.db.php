<?php

function installFeature()
{
  GLOBAL $db;

  $query = new CreateQuery('features');
  $query->addField('id', 'INTEGER', 0, array('A', 'P'), 0);
  $query->addField('name', 'TEXT', 32, array('N'), 0);
  $query->addField('description', 'TEXT', 1024, array('N'), 0);
  $db->create($query);

}
