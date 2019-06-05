<?php

function installBackgroundTraits()
{
  GLOBAL $db;

  $query = new CreateQuery('background_traits');
  $query->addField('id', 'INTEGER', 0, array('P', 'A'));
  $query->addField('background_id', 'INTEGER', 0, array('N'));
  $query->addField('trait_type_id', 'INTEGER', 0, array('N'));
  $query->addField('description', 'TEXT', 1024, array('N'));
  $db->create($query);
}