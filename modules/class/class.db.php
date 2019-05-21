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

  $query = new CreateQuery('subclass');
  $query->addField('id', 'INTEGER', 0, array('P', 'A'));
  $query->addField('name', 'TEXT', 32, array('N'));
  $query->addField('class_id', 'INTEGER', array('N'));
  $query->addField('source_id', 'INTEGER');
  $query->addField('description', 'TEXT', 1024);
  $db->create($query);
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

/******************************************************************************
 *
 *  Subclass.
 *
 ******************************************************************************/

/**
 * @param int $class_id
 * @param int $page
 *
 * @return array|bool
 */
function getSubclassPager($class_id, $page = 1)
{
  GLOBAL $db;

  $query = new SelectQuery('subclasses');
  $query->addField('id');
  $query->addField('name');
  $query->addField('description');
  $query->addConditionSimple('class_id', $class_id);
  $query->addPager($page);
  $results = $db->select($query);

  if (!$results)
  {
    return array();
  }
  return $results;
}

/**
 * @param int|bool $class_id - optional. Filter by given class if present.
 *
 * @return array
 */
function getSubclassList($class_id = FALSE)
{
  GLOBAL $db;

  $query = new SelectQuery('subclasses');
  $query->addField('id')->addField('name', 'value');
  if ($class_id)
  {
    $query->addConditionSimple('class_id', $class_id);
  }

  return $db->selectList($query);
}

/**
 * @param int $sublcass_id
 *
 * @return array|FALSE
 */
function getSubclass($sublcass_id)
{
  GLOBAL $db;

  $query = new SelectQuery('subclasses');
  $query->addField('id');
  $query->addField('class_id');
  $query->addField('name');
  $query->addField('description');
  $query->addField('source_id');
  $query->addConditionSimple('id', $sublcass_id);

  $results = $db->select($query);
  if (!$results)
  {
    return FALSE;
  }
  $result = array_shift($results);
  return $result;
}

/**
 * @param array $subclass
 *
 * @return int|false
 */
function createSubclass($subclass)
{
  GLOBAL $db;

  $query = new InsertQuery('subclasses');
  $query->addField('class_id', $subclass['class_id']);
  $query->addField('name', $subclass['name']);
  $query->addField('description', $subclass['description']);
  $query->addField('source_id', $subclass['source_id']);

  return $db->insert($query);
}

/**
 * @param array $subclass
 */
function updateSubclass($subclass)
{
  GLOBAL $db;

  $query = new UpdateQuery('subclasses');
  $query->addField('name', $subclass['name']);
  $query->addField('description', $subclass['description']);
  $query->addField('source_id', $subclass['source_id']);
  $query->addConditionSimple('id', $subclass['id']);

  $db->update($query);
}

/**
 * @param int $subclass_id
 */
function deleteSubclass($subclass_id)
{
  GLOBAL $db;

  $query = new DeleteQuery('subclasses');
  $query->addConditionSimple('id', $subclass_id);
  $db->delete($query);
}
