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
  $query->addField('id', 'INTEGER', 0, array('P', 'A'));
  $query->addField('name', 'TEXT', 32, array('N'));
  $query->addField('description', 'TEXT', 1024);
  $query->addField('hit_die', 'INTEGER');
  $query->addField('stp1', 'INTEGER');
  $query->addField('stp2', 'INTEGER');
  $query->addField('subclass_name', 'TEXT', 32);
  $query->addField('source_id', 'INTEGER');
  $db->create($query);

  $sources = array_flip(getSourceList());
  $abilities = array_flip(getAbilityCodeList());
  $classes = array(
    array(
      'name' => 'Cleric',
      'description' => '',
      'hit_die' => 8,
      'stp1' => $abilities['WIS'],
      'stp2' => $abilities['CHA'],
      'subclass_name' => 'Divine Domain',
      'source_id' => $sources['BR'],
    ),
    array(
      'name' => 'Fighter',
      'description' => '',
      'hit_die' => 10,
      'stp1' => $abilities['STR'],
      'stp2' => $abilities['CON'],
      'subclass_name' => 'Martial Archetype',
      'source_id' => $sources['BR'],
    ),
    array(
      'name' => 'Rogue',
      'description' => '',
      'hit_die' => 8,
      'stp1' => $abilities['DEX'],
      'stp2' => $abilities['INT'],
      'subclass_name' => 'Roguish Archetype',
      'source_id' => $sources['BR'],
    ),
    array(
      'name' => 'Wizard',
      'description' => '',
      'hit_die' => 6,
      'stp1' => $abilities['INT'],
      'stp2' => $abilities['WIS'],
      'subclass_name' => 'Arcane Tradition',
      'source_id' => $sources['BR'],
    ),
  );

  foreach ($classes as $class)
  {
    createClass($class);
  }
}


/******************************************************************************
 *
 *  Class.
 *
 ******************************************************************************/

/**
 * @param int $page
 *
 * @return array|false
 */
function getClassPager($page = 1)
{
  GLOBAL $db;

  $query = new SelectQuery('classes');
  $query->addField('id');
  $query->addField('name');
  $query->addField('hit_die');
  $query->addField('description');
  $query->addField('source_id');
  $query->addOrderSimple('name');
  $query->addPager($page);

  $results = $db->select($query);

  if (!$results)
  {
    return array();
  }
  return $results;
}

function getClassList()
{
  GLOBAL $db;

  $query = new SelectQuery('classes');
  $query->addField('id')->addField('name', 'value');

  return $db->selectList($query);
}

/**
 * @param int $class_id
 *
 * @return bool|mixed
 */
function getClass($class_id)
{
  GLOBAL $db;

  $query = new SelectQuery('classes');
  $query->addField('id');
  $query->addField('name');
  $query->addField('description');
  $query->addField('hit_die');
  $query->addField('stp1');
  $query->addField('stp2');
  $query->addField('subclass_name');
  $query->addField('source_id');
  $query->addConditionSimple('id', $class_id);

  return $db->selectObject($query);
}

/**
 * @param array $class
 *
 * @return bool|int|string
 */
function createClass($class)
{
  GLOBAL $db;

  $query = new InsertQuery('classes');
  $query->addField('name', $class['name']);
  $query->addField('description', $class['description']);
  $query->addField('hit_die', $class['hit_die']);
  $query->addField('stp1', $class['stp1']);
  $query->addField('stp2', $class['stp2']);
  $query->addField('subclass_name', $class['subclass_name']);
  $query->addField('source_id', $class['source_id']);

  return $db->insert($query);
}

function updateClass($class)
{
  GLOBAL $db;

  $query = new UpdateQuery('classes');
  $query->addField('name', $class['name']);
  $query->addField('description', $class['description']);
  $query->addField('hit_die', $class['hit_die']);
  $query->addField('stp1', $class['stp1']);
  $query->addField('stp2', $class['stp2']);
  $query->addField('subclass_name', $class['subclass_name']);
  $query->addField('source_id', $class['source_id']);
  $query->addConditionSimple('id', $class['id']);

  $db->update($query);
}

function deleteClass($id)
{
  GLOBAL $db;

  $query = new DeleteQuery('classes');
  $query->addConditionSimple('id', $id);
  $db->delete($query);
}
