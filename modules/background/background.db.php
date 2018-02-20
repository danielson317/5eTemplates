<?php

/******************************************************************************
 *
 *  Character.
 *
 ******************************************************************************/
function installBackground()
{
  GLOBAL $db;

  $query = new CreateQuery('background');
  $query->addField('id', 'INTEGER', array('P', 'A'));
  $query->addField('name', 'TEXT', array('N'));
  $query->addField('description', 'TEXT');
  $query->addField('skill_count', 'INTEGER', array('N'), 2);
  $db->create($query);

  $query = new CreateQuery('traits');
  $query->addField('id', 'INTEGER', array('P', 'A'));
  $query->addField('background_id', 'INTEGER', array('N'));
  $query->addField('trait_type_id', 'INTEGER', array('N'));
  $query->addField('description', 'TEXT', array('N'));
  $db->create($query);

  $query = new CreateQuery('trait_types');
  $query->addField('id', 'INTEGER', array('P', 'A'));
  $query->addField('name', 'TEXT', array('N'));
  $query->addField('description', 'TEXT');
  $db->create($query);

  $query = new CreateQuery('background_skill');
  $query->addField('background_id', 'INTEGER', array('P', 'N'));
  $query->addField('skill_id', 'INTEGER', array('P', 'N'));
  $db->create($query);

  $query = new CreateQuery('background_proficiency');
  $query->addField('background_id', 'INTEGER', array('P', 'N'));
  $query->addField('proficiency_id', 'INTEGER', array('P', 'N'));
  $query->addField('proficiency_type_id', 'INTEGER', array('P', 'N'), 0);
  $db->create($query);
}
