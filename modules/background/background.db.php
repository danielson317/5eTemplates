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
  $query->addField('id', 'INTEGER', 0, array('P', 'A'));
  $query->addField('name', 'TEXT', 0, array('N'));
  $query->addField('description', 'TEXT', 1024);
  $query->addField('skill_count', 'INTEGER', 0, array('N'), 2);
  $db->create($query);

  $query = new CreateQuery('traits');
  $query->addField('id', 'INTEGER', 0, array('P', 'A'));
  $query->addField('background_id', 'INTEGER', 0, array('N'));
  $query->addField('trait_type_id', 'INTEGER', 0, array('N'));
  $query->addField('description', 'TEXT', 1024, array('N'));
  $db->create($query);

  $query = new CreateQuery('trait_types');
  $query->addField('id', 'INTEGER', 0, array('P', 'A'));
  $query->addField('name', 'TEXT', 32, array('N'));
  $query->addField('description', 'TEXT', 1024);
  $db->create($query);

  $query = new CreateQuery('background_skill');
  $query->addField('background_id', 'INTEGER', 0, array('P', 'N'));
  $query->addField('skill_id', 'INTEGER', 0, array('P', 'N'));
  $db->create($query);

  $query = new CreateQuery('background_proficiency');
  $query->addField('background_id', 'INTEGER', 0, array('P', 'N'));
  $query->addField('proficiency_id', 'INTEGER', 0, array('P', 'N'));
  $query->addField('proficiency_type_id', 'INTEGER', 0, array('P', 'N'), 0);
  $db->create($query);
}

/***********************************
 *
 *  Background
 *
 **********************************/
function getBackgroundPager($page)
{
  GLOBAL $db;

  $query = new SelectQuery('background');
  $query->addField('id');
  $query->addField('name');
  $query->addField('description');
  $query->addField('skill_count');
  $query->addPager($page);

  return $db->select($query);
}

function getBackground($id)
{
  GLOBAL $db;

  $query = new SelectQuery('background');
  $query->addField('id');
  $query->addField('name');
  $query->addField('description');
  $query->addField('skill_count');
  $query->addConditionSimple('id', $id);

  return $db->selectObject($query);
}

function createBackground($background)
{
  GLOBAL $db;
  
  $query = new CreateQuery('background');
  
  $query->addField('id', $background['id']);
  $query->addField('name', $background['name']);
  $query->addField('description', $background['description']);
  $query->addField('skill_count', $background['skill_count']);
  $query->addConditionSimple('id', $id);
  
  return $db->create($query);
}

function updateBackground($background)
{
  GLOBAL $db;
  
  $query = new UpdateQuery('background');
  
  $query->addField('id', $background['id']);
  $query->addField('name', $background['name']);
  $query->addField('description', $background['description']);
  $query->addField('skill_count', $background['skill_count']);
  $query->addConditionSimple('id', $id);
  
  return $db->update($query);
}

/***********************************
 *
 *  Trait
 *
 **********************************/
function getTraitPager($page)
{
  GLOBAL $db;

  $query = new SelectQuery('traits');
  $query->addField('id');
  $query->addField('name');
  $query->addField('description');
  $query->addField('skill_count');
  $query->addPager($page);

  return $db->select($query);
}


