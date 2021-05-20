<?php

/******************************************************************************
 *
 *  Character.
 *
 ******************************************************************************/
function installBackground()
{
  GLOBAL $db;

  $query = new CreateQuery('backgrounds');
  $query->addField('id', CreateQuery::TYPE_INTEGER, 0, array('P', 'A'));
  $query->addField('name', CreateQuery::TYPE_STRING, 0, array('N'));
  $query->addField('description', CreateQuery::TYPE_STRING, 1024);
  $query->addField('source_id', CreateQuery::TYPE_INTEGER, 0, array('N'), 0);
  $query->addField('source_location', CreateQuery::TYPE_INTEGER);
  $db->create($query);

  $sources = array_flip(getSourceList());
  $backgrounds = array(
    array(
      'name' => 'Acolyte',
      'description' => '',
      'source_id' => $sources['BR'],
    ),
    array(
      'name' => 'Criminal',
      'description' => '',
      'source_id' => $sources['BR'],
    ),
    array(
      'name' => 'Folk Hero',
      'description' => '',
      'source_id' => $sources['BR'],
    ),
    array(
      'name' => 'Noble',
      'description' => '',
      'source_id' => $sources['BR'],
    ),
    array(
      'name' => 'Sage',
      'description' => '',
      'source_id' => $sources['BR'],
    ),
    array(
      'name' => 'Soldier',
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

  $query = new SelectQuery('backgrounds');
  $query->addField('id');
  $query->addField('name');
  $query->addField('description');
  $query->addField('source_id');
  $query->addPager($page);

  return $db->select($query);
}

function getBackground($id)
{
  GLOBAL $db;

  $query = new SelectQuery('backgrounds');
  $query->addField('id');
  $query->addField('name');
  $query->addField('description');
  $query->addField('source_id');
  $query->addConditionSimple('id', $id);

  return $db->selectObject($query);
}

function getBackgroundList()
{
  GLOBAL $db;

  $query = new SelectQuery('backgrounds');
  $query->addField('id');
  $query->addField('name', 'value');

  return $db->selectList($query);
}

function createBackground($background)
{
  GLOBAL $db;
  
  $query = new InsertQuery('backgrounds');
  $query->addField('name', $background['name']);
  $query->addField('description', $background['description']);
  $query->addField('source_id', $background['source_id']);

  return $db->insert($query);
}

function updateBackground($background)
{
  GLOBAL $db;
  
  $query = new UpdateQuery('backgrounds');
  
  $query->addField('name', $background['name']);
  $query->addField('description', $background['description']);
  $query->addField('skill_count', $background['skill_count']);
  $query->addConditionSimple('id', $background['id']);
  
  $db->update($query);
}
