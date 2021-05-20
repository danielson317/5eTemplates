<?php

function installSource()
{
  GLOBAL $db;

  $query = new CreateQuery('sources');
  $query->addField('id', CreateQuery::TYPE_INTEGER, 0, array('P', 'A'));
  $query->addField('code', CreateQuery::TYPE_STRING, 8, array('N'));
  $query->addField('name', CreateQuery::TYPE_STRING, 32, array('N'));
  $db->create($query);

  $sources = array(
    array(
      'code' => 'BR',
      'name' => 'Basic Rules',
    ),
    array(
      'code' => 'PHB',
      'name' => 'Player Handbook',
    ),
    array(
      'code' => 'DMG',
      'name' => 'Dungeon Master\'s Guide',
    ),
    array(
      'code' => 'MM',
      'name' => 'Monster Manual',
    ),
    array(
      'code' => 'SCAG',
      'name' => 'Sword Coast Adventurer\'s Guide',
    ),
    array(
      'code' => 'VGM',
      'name' => 'Volo\'s guid to Monsters',
    ),
    array(
      'code' => 'MTF',
      'name' => 'Mordenkainen\'s Tome of Foes',
    ),
    array(
      'code' => 'XGE',
      'name' => 'Xanathar\'s Guide to Everything',
    ),
    array(
      'code' => 'TCE',
      'name' => 'Tasha\'s Cauldron of Everything',
    ),
    array(
      'code' => 'HB',
      'name' => 'Homebrew',
    ),
  );

  foreach ($sources as $source)
  {
    createSource($source);
  }
}

/**
 * @param int $page
 *
 * @return array|false
 */
function getSourcePager($page = 1)
{
  GLOBAL $db;

  $query = new SelectQuery('sources');
  $query->addField('id');
  $query->addField('name');
  $query->addField('code');
  $query->addPager($page);

  return $db->select($query);
}

/**
 * @return array
 */
function getSourceList()
{
  GLOBAL $db;

  $query = new SelectQuery('sources');
  $query->addField('id');
  $query->addField('code', 'value');

  return $db->selectList($query);
}

/**
 * @return array
 */
function getSourceDetailList()
{
  GLOBAL $db;

  $query = new SelectQuery('sources');
  $query->addField('id');
  $query->addFieldBypass($db->concatenate(
    $db->structureEscape('sources') . '.' . $db->structureEscape('code'),
    $db->literal(' - '),
    $db->structureEscape('sources') . '.' . $db->structureEscape('name')), 'value');

  return $db->selectList($query);
}

/**
 * @param $source_id
 *
 * @return array|FALSE
 */
function getSource($source_id)
{
  GLOBAL $db;

  $query = new SelectQuery('sources');
  $query->addField('id');
  $query->addField('name');
  $query->addField('code');
  $query->addConditionSimple('id', $source_id);

  return $db->selectObject($query);
}

/**
 * @param array $source
 *
 * @return int
 */
function createSource($source)
{
  GLOBAL $db;

  $query = new InsertQuery('sources');
  $query->addField('name', $source['name']);
  $query->addField('code', $source['code']);

  return $db->insert($query);
}

/**
 * @param $source
 */
function updateSource($source)
{
  GLOBAL $db;

  $query = new UpdateQuery('sources');
  $query->addField('name', $source['name']);
  $query->addField('code', $source['code']);
  $query->addConditionSimple('id', $source['id']);

  $db->update($query);
}

/**
 * @param int $source_id
 */
function deleteSource($source_id)
{
  GLOBAL $db;

  $query = new DeleteQuery('sources');
  $query->addConditionSimple('id', $source_id);

  $db->delete($query);
}
