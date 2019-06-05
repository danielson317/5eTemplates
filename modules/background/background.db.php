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
  $query->addField('source_id', 'INTEGER', 0, array('N'), 0);
  $db->create($query);

  $sources = array_flip(getSourceList());
  $backgrounds = array(
    array(
      'name' => 'Acolyte',
      'description' => '',
      'source_id' => $sources['BR'],
    ),
  );
  foreach ($backgrounds as $background)
  {
    createBackground($background);
  }
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
  $query->addConditionSimple('id', $background['id']);
  
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


