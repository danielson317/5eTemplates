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
  $query->addField('id', 'INTEGER', array('P', 'A'));
  $query->addField('name', 'TEXT', array('N'));
  $query->addField('description', 'TEXT');
  $query->addField('hit_die', 'INTEGER');
  $query->addField('stp1', 'INTEGER');
  $query->addField('stp2', 'INTEGER');
  $query->addField('subclass_name', 'TEXT');
  $query->addField('source_id', 'INTEGER');
  $db->create($query);

  $query = new CreateQuery('subclass');
  $query->addField('id', 'INTEGER', array('P', 'A'));
  $query->addField('name', 'TEXT', array('N'));
  $query->addField('class_id', 'INTEGER', array('N'));
  $query->addField('source_id', 'INTEGER');
  $query->addField('description', 'TEXT');
  $db->create($query);
}


/******************************************************************************
 *
 *  Class.
 *
 ******************************************************************************/

function getClassPager($page = 1)
{
  GLOBAL $db;

  $query = new SelectQuery('classes');
  $query->addField('id')
        ->addField('name')
        ->addField('hit_die')
        ->addField('description')
        ->addField('source_id')
        ->addOrder('name')
        ->addPager($page);

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

function getClass($id)
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
  $query->addCondition('id');
  $args = array(':id' => $id);

  $results = $db->select($query, $args);
  if (!$results)
  {
    return FALSE;
  }
  $result = array_shift($results);
  return $result;
}

function createClass($class)
{
  GLOBAL $db;

  $query = new InsertQuery('classes');
  $query->addField('name');
  $query->addField('description');
  $query->addField('hit_die');
  $query->addField('stp1');
  $query->addField('stp2');
  $query->addField('subclass_name');
  $query->addField('source_id');
  $args = $db->buildArgs($class);

  return $db->insert($query, $args);
}

function updateClass($class)
{
  GLOBAL $db;

  $query = new UpdateQuery('classes');
  $query->addField('name');
  $query->addField('description');
  $query->addField('hit_die');
  $query->addField('stp1');
  $query->addField('stp2');
  $query->addField('subclass_name');
  $query->addField('source_id');
  $query->addCondition('id');
  $args = $db->buildArgs($class);

  $db->update($query, $args);
}

function deleteClass($id)
{
  GLOBAL $db;

  $query = new DeleteQuery('classes');
  $query->addCondition('id');
  $args = array(':id' => $id);

  $db->delete($query, $args);
}

/******************************************************************************
 *
 *  Subclass.
 *
 ******************************************************************************/

function getSubclassPager($class_id, $page = 1)
{
  GLOBAL $db;

  $query = new SelectQuery('subclasses');
  $query->addField('id');
  $query->addField('name');
  $query->addField('description');
  $query->addCondition('class_id');
  $query->addPager($page);
  $args = array(':class_id' => $class_id);

  $results = $db->select($query, $args);

  if (!$results)
  {
    return array();
  }
  return $results;
}

function getSubclassList($class_id = FALSE)
{
  GLOBAL $db;

  $query = new SelectQuery('subclasses');
  $query->addField('id')->addField('name', 'value');
  $args = array();
  if ($class_id)
  {
    $query->addCondition('class_id');
    $args[':class_id'] = $class_id;
  }

  return $db->selectList($query, $args);
}

function getSubclass($id)
{
  GLOBAL $db;

  $query = new SelectQuery('subclasses');
  $query->addField('id');
  $query->addField('class_id');
  $query->addField('name');
  $query->addField('description');
  $query->addField('source_id');
  $query->addCondition('id');
  $args = array(':id' => $id);

  $results = $db->select($query, $args);
  if (!$results)
  {
    return FALSE;
  }
  $result = array_shift($results);
  return $result;
}

function createSubclass($class)
{
  GLOBAL $db;

  $query = new InsertQuery('subclasses');
  $query->addField('class_id');
  $query->addField('name');
  $query->addField('description');
  $query->addField('source_id');
  $args = $db->buildArgs($class);

  return $db->insert($query, $args);
}

function updateSubclass($class)
{
  GLOBAL $db;

  $query = new UpdateQuery('subclasses');
  $query->addField('name');
  $query->addField('description');
  $query->addField('source_id');
  $query->addCondition('id');
  $args = $db->buildArgs($class);

  $db->update($query, $args);
}

function deleteSubclass($id)
{
  GLOBAL $db;

  $query = new DeleteQuery('subclasses');
  $query->addCondition('id');
  $args = array(':id' => $id);

  $db->delete($query, $args);
}
