<?php

function installRarity()
{
  GLOBAL $db;

  // Conjuration, evocation, etc.
  $query = new CreateQuery('rarities');
  $query->addField('id', 'INTEGER', 0, array('P', 'A'));
  $query->addField('name', 'TEXT', 32, array('N'));
  $query->addField('description', 'TEXT', 1024);
  $db->create($query);

  $rarities = array(
    array(
      'name' => 'Common',
      'description' => '',
    ),
    array(
      'name' => 'Uncommon',
      'description' => '',
    ),
    array(
      'name' => 'Rare',
      'description' => '',
    ),
    array(
      'name' => 'Very Rare',
      'description' => '',
    ),
    array(
      'name' => 'Legendary',
      'description' => '',
    ),
  );

  foreach ($rarities as $rarity)
  {
    createRarity($rarity);
  }
}

/**
 * @param int $page
 *
 * @return array|false
 */
function getRarityPager($page)
{
  GLOBAL $db;

  $query = new SelectQuery('rarities');
  $query->addField('id');
  $query->addField('name');
  $query->addField('description');
  $query->addPager($page);

  return $db->select($query);
}

/**
 * @return array
 */
function getRarityList()
{
  GLOBAL $db;

  $query = new SelectQuery('rarities');
  $query->addField('id')->addField('name', 'value');

  return $db->selectList($query);
}

/**
 * @param int $rarity_id
 *
 * @return array|false
 */
function getRarity($rarity_id)
{
  GLOBAL $db;

  $query = new SelectQuery('rarities');
  $query->addField('id');
  $query->addField('name');
  $query->addField('description');
  $query->addConditionSimple('id', $rarity_id);

  return $db->selectObject($query);
}

/**
 * @param array $rarity
 *
 * @return int
 */
function createRarity($rarity)
{
  GLOBAL $db;

  $query = new InsertQuery('rarities');
  $query->addField('name', $rarity['name']);
  $query->addField('description', $rarity['description']);

  return $db->insert($query);
}

/**
 * @param array $rarity
 */
function updateRarity($rarity)
{
  GLOBAL $db;

  $query = new updateQuery('rarities');
  $query->addField('name', $rarity['name']);
  $query->addField('description', $rarity['description']);
  $query->addConditionSimple('id', $rarity['id']);

  $db->update($query);
}

/**
 * @param int $rarity_id
 */
function deleteRarity($rarity_id)
{
  GLOBAL $db;

  $query = new DeleteQuery('rarities');
  $query->addConditionSimple('id', $rarity_id);

  $db->delete($query);
}
