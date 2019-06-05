<?php

function installSubrace()
{
  GLOBAL $db;

  $query = new CreateQuery('subraces');
  $query->addField('id', 'INTEGER', 0, array('P', 'A'));
  $query->addField('race_id', 'INTEGER', 0, array('N'), 0);
  $query->addField('name', 'TEXT', 32, array('N'));
  $query->addField('description', 'TEXT', 1024);
  $query->addField('source_id', 'INTEGER');
  $db->create($query);

  $sources = array_flip(getSourceList());
  $races = array_flip(getRaceList());
  $races = array(
    array(
      'race_id' => $races['Dwarf'],
      'name' => 'Hill Dwarf',
      'description' => '',
      'source_id' => $sources['BR'],
    ),
    array(
      'race_id' => $races['Dwarf'],
      'name' => 'Mountain Dwarf',
      'description' => '',
      'source_id' => $sources['BR'],
    ),
    array(
      'race_id' => $races['Elf'],
      'name' => 'Wood Elf',
      'description' => '',
      'source_id' => $sources['BR'],
    ),
    array(
      'race_id' => $races['Elf'],
      'name' => 'High Elf',
      'description' => '',
      'source_id' => $sources['BR'],
    ),
    array(
      'race_id' => $races['Halfling'],
      'name' => 'Lightfoot Halfling',
      'description' => '',
      'source_id' => $sources['BR'],
    ),
    array(
      'race_id' => $races['Halfling'],
      'name' => 'Stout Halfling',
      'description' => '',
      'source_id' => $sources['BR'],
    ),
  );

  foreach ($races as $race)
  {
    createRace($race);
  }
}

/**
 * @param int $race_id
 * @param int $page
 *
 * @return array|false
 */
function getSubracePager($race_id, $page = 1)
{
  GLOBAL $db;

  $query = new SelectQuery('subraces');
  $query->addField('id');
  $query->addField('name');
  $query->addField('description');
  $query->addField('source_id');
  $query->addConditionSimple('race_id', $race_id);
  $query->addPager($page);
  return $db->select($query);
}

/**
 * @param int|bool $race_id
 *
 * @return array
 */
function getSubraceList($race_id = FALSE)
{
  GLOBAL $db;

  $query = new SelectQuery('subraces');
  $query->addField('id')->addField('name', 'value');
  if ($race_id)
  {
    $query->addConditionSimple('race_id', $race_id);
  }

  return $db->selectList($query);
}

/**
 * @param $subrace_id
 *
 * @return array|false
 */
function getSubrace($subrace_id)
{
  GLOBAL $db;

  $query = new SelectQuery('subraces');
  $query->addField('id');
  $query->addField('race_id');
  $query->addField('name');
  $query->addField('description');
  $query->addField('source_id');
  $query->addConditionSimple('id', $subrace_id);

  return $db->selectObject($query);
}

/**
 * @param array $subrace
 *
 * @return int
 */
function createSubrace($subrace)
{
  GLOBAL $db;

  $query = new InsertQuery('subraces');
  $query->addField('race_id', $subrace['race_id']);
  $query->addField('name', $subrace['name']);
  $query->addField('description', $subrace['description']);
  $query->addField('source_id', $subrace['source_id']);

  return $db->insert($query);
}

/**
 * @param array $subrace
 */
function updateSubrace($subrace)
{
  GLOBAL $db;

  $query = new UpdateQuery('subraces');
  $query->addField('name', $subrace['name']);
  $query->addField('description', $subrace['description']);
  $query->addField('source_id', $subrace['source_id']);
  $query->addConditionSimple('id', $subrace['id']);

  $db->update($query);
}

/**
 * @param int $subrace_id
 */
function deleteSubrace($subrace_id)
{
  GLOBAL $db;

  $query = new DeleteQuery('subraces');
  $query->addConditionSimple('id', $subrace_id);

  $db->delete($query);
}
