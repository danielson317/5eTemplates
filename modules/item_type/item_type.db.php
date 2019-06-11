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

  $item_types = array(
    array(
      'name' => 'Weapon',
      'parent_item_type_id' => 0,
      'description' => '',
    ),
    array(
      'name' => 'Armor',
      'parent_item_type_id' => 0,
      'description' => '',
    ),
    array(
      'name' => 'Adventuring Gear',
      'parent_item_type_id' => 0,
      'description' => '',
    ),
    array(
      'name' => 'Container',
      'parent_item_type_id' => 0,
      'description' => '',
    ),
    array(
      'name' => 'Tool',
      'parent_item_type_id' => 0,
      'description' => '',
    ),
    array(
      'name' => 'Mount or Vehicle',
      'parent_item_type_id' => 0,
      'description' => '',
    ),
    array(
      'name' => 'Trade Good',
      'parent_item_type_id' => 0,
      'description' => '',
    ),
    array(
      'name' => 'Food, Drink, and Lodging',
      'parent_item_type_id' => '',
      'description' => '',
    ),
    array(
      'name' => 'Service',
      'parent_item_type_id' => 0,
      'description' => '',
    ),
    array(
      'name' => 'Trinket',
      'parent_item_type_id' => 0,
      'description' => '',
    ),
  );
  foreach($item_types as $item_type)
  {
    createItemType($item_type);
  }

  // Weapon categories.
  $item_type_list = array_flip(getItemTypeList());
  $item_types = array(
    array(
      'name' => 'Simple Melee Weapon',
      'parent_item_type_id' => $item_type_list['Weapon'],
      'description' => '',
    ),
    array(
      'name' => 'Simple Ranged Weapon',
      'parent_item_type_id' => $item_type_list['Weapon'],
      'description' => '',
    ),
    array(
      'name' => 'Martial Melee Weapon',
      'parent_item_type_id' => $item_type_list['Weapon'],
      'description' => '',
    ),
    array(
      'name' => 'Martial Ranged Weapon',
      'parent_item_type_id' => $item_type_list['Weapon'],
      'description' => '',
    ),
  );
  foreach($item_types as $item_type)
  {
    createItemType($item_type);
  }

  // Armor Categories.
  $item_type_list = array_flip(getItemTypeList());
  $item_types = array(
    array(
      'name' => 'Light Armor',
      'parent_item_type_id' => $item_type_list['Armor'],
      'description' => '',
    ),
    array(
      'name' => 'Medium Armor',
      'parent_item_type_id' => $item_type_list['Armor'],
      'description' => '',
    ),
    array(
      'name' => 'Heavy Armor',
      'parent_item_type_id' => $item_type_list['Armor'],
      'description' => '',
    ),
    array(
      'name' => 'Shield',
      'parent_item_type_id' => $item_type_list['Armor'],
      'description' => '',
    ),
  );
  foreach($item_types as $item_type)
  {
    createItemType($item_type);
  }

  // Armor Categories.
  $item_type_list = array_flip(getItemTypeList());
  $item_types = array(
    array(
      'name' => 'Ammunition',
      'parent_item_type_id' => $item_type_list['Adventuring Gear'],
      'description' => '',
    ),
    array(
      'name' => 'Arcane Focus',
      'parent_item_type_id' => $item_type_list['Adventuring Gear'],
      'description' => '',
    ),
    array(
      'name' => 'Druidic Focus',
      'parent_item_type_id' => $item_type_list['Adventuring Gear'],
      'description' => '',
    ),
    array(
      'name' => 'Holy Symbol',
      'parent_item_type_id' => $item_type_list['Adventuring Gear'],
      'description' => '',
    ),
  );
  foreach($item_types as $item_type)
  {
    createItemType($item_type);
  }

  // Tools.
  $item_type_list = array_flip(getItemTypeList());
  $item_types = array(
    array(
      'name' => 'Artisan\'s Tools',
      'parent_item_type_id' => $item_type_list['Tool'],
      'description' => '',
    ),
    array(
      'name' => 'Gaming Set',
      'parent_item_type_id' => $item_type_list['Tool'],
      'description' => '',
    ),
    array(
      'name' => 'Musical Instrument',
      'parent_item_type_id' => $item_type_list['Tool'],
      'description' => '',
    ),
  );
  foreach($item_types as $item_type)
  {
    createItemType($item_type);
  }
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
  $query->addField('parent_item_type_id');
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
  $query->addField('parent_item_type_id');
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
  $query->addField('parent_item_type_id', $item_type['parent_item_type_id']);
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
  $query->addField('parent_item_type_id', $item_type['parent_item_type_id']);
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
