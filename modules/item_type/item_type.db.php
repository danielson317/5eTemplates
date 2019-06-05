<?php

function installItemType()
{
  GLOBAL $db;

  // Armor, Gemstone, Wand, Weapon, etc.
  $query = new CreateQuery('item_types');
  $query->addField('id', 'INTEGER', 0, array('P', 'A'));
  $query->addField('name', 'TEXT', 32, array('N'));
  $query->addField('parent_item_type_id', 'INTEGER', 0, array('N'), 0);
  $query->addField('description', 'TEXT', 1024);
  $db->create($query);
}

/**
 * @param int $page
 *
 * @return array|false
 */
function getItemTypePager($page = 1)
{
  GLOBAL $db;

  $query = new SelectQuery('item_types');
  $query->addField('id');
  $query->addField('name');
  $query->addField('description');
  $query->addOrderSimple('id');
  $query->addPager($page);

  return $db->select($query);
}

/**
 * @return array
 */
function getItemTypeList()
{
  GLOBAL $db;

  $query = new SelectQuery('item_types');
  $query->addField('id')->addField('name', 'value');

  return $db->selectList($query);
}

/**
 * @param $item_type_id
 *
 * @return array|false
 */
function getItemType($item_type_id)
{
  GLOBAL $db;

  $query = new SelectQuery('item_types');
  $query->addField('id');
  $query->addField('name');
  $query->addField('description');
  $query->addConditionSimple('id', $item_type_id);
  return $db->selectObject($query);
}

/**
 * @param array $item_type
 *
 * @return int
 */
function createItemType($item_type)
{
  GLOBAL $db;

  $query = new InsertQuery('item_types');
  $query->addField('name', $item_type['name']);
  $query->addField('description', $item_type['description']);

  return $db->insert($query);
}

/**
 * @param array $item_type
 */
function updateItemType($item_type)
{
  GLOBAL $db;

  $query = new UpdateQuery('item_types');
  $query->addField('name', $item_type['name']);
  $query->addField('description', $item_type['description']);
  $query->addConditionSimple('id', $item_type['id']);

  $db->update($query);
}

/**
 * @param $item_type_id
 */
function deleteItemType($item_type_id)
{
  GLOBAL $db;
  $query = new DeleteQuery('item_types');
  $query->addConditionSimple('id', $item_type_id);
  $db->delete($query);
}
