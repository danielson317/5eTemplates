<?php

function installSchool()
{
  GLOBAL $db;

  // Conjuration, evocation, etc.
  $query = new CreateQuery('schools');
  $query->addField('id', 'INTEGER', 0, array('P', 'A'));
  $query->addField('name', 'TEXT', 32, array('N'));
  $query->addField('description', 'TEXT', 1024);
  $db->create($query);

  $schools = array(
    array(
      'name' => 'Abjuration',
      'description' => '',
    ),
    array(
      'name' => 'Conjuration',
      'description' => '',
    ),
    array(
      'name' => 'Divination',
      'description' => '',
    ),
    array(
      'name' => 'Enchantment',
      'description' => '',
    ),
    array(
      'name' => 'Evocation',
      'description' => '',
    ),
    array(
      'name' => 'Illusion',
      'description' => '',
    ),
    array(
      'name' => 'Necromancy',
      'description' => '',
    ),
    array(
      'name' => 'Transmutation',
      'description' => '',
    ),
  );

  foreach ($schools as $school)
  {
    createSchool($school);
  }
}

/**
 * @param int $page
 *
 * @return array|false
 */
function getSchoolPager($page)
{
  GLOBAL $db;

  $query = new SelectQuery('schools');
  $query->addField('id');
  $query->addField('name');
  $query->addField('description');
  $query->addPager($page);

  return $db->select($query);
}

/**
 * @return array
 */
function getSchoolList()
{
  GLOBAL $db;

  $query = new SelectQuery('schools');
  $query->addField('id')->addField('name', 'value');

  return $db->selectList($query);
}

/**
 * @param int $school_id
 *
 * @return array|false
 */
function getSchool($school_id)
{
  GLOBAL $db;

  $query = new SelectQuery('schools');
  $query->addField('id');
  $query->addField('name');
  $query->addField('description');
  $query->addConditionSimple('id', $school_id);

  return $db->selectObject($query);
}

/**
 * @param array $school
 *
 * @return int
 */
function createSchool($school)
{
  GLOBAL $db;

  $query = new InsertQuery('schools');
  $query->addField('name', $school['name']);
  $query->addField('description', $school['description']);

  return $db->insert($query);
}

/**
 * @param array $school
 */
function updateSchool($school)
{
  GLOBAL $db;

  $query = new updateQuery('schools');
  $query->addField('name', $school['name']);
  $query->addField('description', $school['description']);
  $query->addConditionSimple('id', $school['id']);

  $db->update($query);
}

/**
 * @param int $school_id
 */
function deleteSchool($school_id)
{
  GLOBAL $db;

  $query = new DeleteQuery('schools');
  $query->addConditionSimple('id', $school_id);

  $db->delete($query);
}
