<?php

function installScript()
{
  GLOBAL $db;

  $query = new CreateQuery('scripts');
  $query->addField('id', CreateQuery::TYPE_INTEGER, 0, array('P', 'A'));
  $query->addField('name', CreateQuery::TYPE_STRING, 32, array('N'));
  $query->addField('description', CreateQuery::TYPE_STRING, 1024);
  $query->addField('source_id', CreateQuery::TYPE_INTEGER);
  $db->create($query);

  $sources = array_flip(getSourceList());
  $scripts = array(
    array(
      'name' => 'Common',
      'source_id' => $sources['BR'],
      'description' => '',
    ),
    array(
      'name' => 'Dwarvish',
      'source_id' => $sources['BR'],
      'description' => '',
    ),
    array(
      'name' => 'Elvish',
      'source_id' => $sources['BR'],
      'description' => '',
    ),
    array(
      'name' => 'Infernal',
      'source_id' => $sources['BR'],
      'description' => '',
    ),
    array(
      'name' => 'Celestial',
      'source_id' => $sources['BR'],
      'description' => '',
    ),
    array(
      'name' => 'Draconic',
      'source_id' => $sources['BR'],
      'description' => '',
    ),
    array(
      'name' => 'Unwritten',
      'source_id' => $sources['BR'],
      'description' => '',
    ),
  );

  foreach ($scripts as $script)
  {
    createScript($script);
  }
}

/**
 * @param int $page
 *
 * @return array|false
 */
function getScriptPager($page = 1)
{
  GLOBAL $db;

  $query = new SelectQuery('scripts');
  $query->addField('id');
  $query->addField('name');
  $query->addField('source_id');
  $query->addField('description');
  $query->addPager($page);

  return $db->select($query);
}

/**
 * @return array
 */
function getScriptList()
{
  GLOBAL $db;

  $query = new SelectQuery('scripts');
  $query->addField('id')->addField('name', 'value');

  return $db->selectList($query);
}

/**
 * @param int $script_id
 *
 * @return array|false
 */
function getScript($script_id)
{
  GLOBAL $db;

  $query = new SelectQuery('scripts');
  $query->addField('id');
  $query->addField('name');
  $query->addField('source_id');
  $query->addField('description');
  $query->addConditionSimple('id', $script_id);
  return $db->selectObject($query);
}

/**
 * @param array $script
 *
 * @return int
 */
function createScript($script)
{
  GLOBAL $db;

  $query = new InsertQuery('scripts');
  $query->addField('name', $script['name']);
  $query->addField('source_id', $script['source_id']);
  $query->addField('description', $script['description']);

  return $db->insert($query);
}

/**
 * @param array $script
 */
function updateScript($script)
{
  GLOBAL $db;

  $query = new UpdateQuery('scripts');
  $query->addField('name', $script['name']);
  $query->addField('source_id', $script['source_id']);
  $query->addField('description', $script['description']);
  $query->addConditionSimple('id', $script['id']);

  $db->update($query);
}

/**
 * @param int $script_id
 */
function deleteScript($script_id)
{
  GLOBAL $db;

  $query = new DeleteQuery('scripts');
  $query->addConditionSimple('id', $script_id);

  $db->delete($query);
}
