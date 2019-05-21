<?php
/******************************************************************************
 *
 *  Install.
 *
 ******************************************************************************/
function installItem()
{
  GLOBAL $db;

  // Bludgeoning, Force, Psychic, etc.
  $query = new CreateQuery('damage_types');
  $query->addField('id', 'INTEGER', 0, array('P', 'A'));
  $query->addField('code', 'TEXT', 8, array('N'));
  $query->addField('name', 'TEXT', 32, array('N'));
  $query->addField('description', 'TEXT', 1024);
  $db->create($query);
  
  // Armor, Gemstone, Wand, Weapon, etc.
  $query = new CreateQuery('item_types');
  $query->addField('id', 'INTEGER', 0, array('P', 'A'));
  $query->addField('name', 'TEXT', 32, array('N'));
  $query->addField('description', 'TEXT', 1024);
  $db->create($query);

  // Sword, Shield, Rome, Boots of butt-kicking, etc.
  $query = new CreateQuery('items');
  $query->addField('id', 'INTEGER', 0, array('P', 'A'));
  $query->addField('name', 'TEXT', 32, array('N'));
  $query->addField('item_type_id', 'INTEGER', 0, array('N'), 0);
  $query->addField('item_type_details', 'TEXT', 64);
  $query->addField('value', 'INTEGER', 0, array('N'), 0);
  $query->addField('weight', 'INTEGER', 0, array('N'), 0);
  $query->addField('rarity_id', 'INTEGER', 0, array('N'), 0);
  $query->addField('attunement', 'INTEGER', 0, array('N'), 0);
  $query->addField('attunement_requirements', 'TEXT', 64);
  $query->addField('artifact', 'INTEGER', 0, array('N'), 0);
  $query->addField('description', 'TEXT', 1024);
  $query->addField('source_id', 'INTEGER', 0, array('N'), 0);
  $query->addField('source_location', 'INTEGER', 0, array('N'), 0);

  $query->addField('damage_die_count', 'INTEGER', 0, array('N'), 0);
  $query->addField('damage_die', 'INTEGER', 0, array('N'), 0);
  $query->addField('damage_type_id', 'INTEGER', 0, array('N'), 0);
  $query->addField('range_id', 'INTEGER', 0, array('N'), 0);
  $query->addField('disadvantage_range_id', 'INTEGER', 0, array('N'), 0);

  $query->addField('ac', 'INTEGER', 0, array('N'), 0);
  $query->addField('strength', 'INTEGER', 0, array('N'), 0);
  $db->create($query);

  // Ammunition, Heavy, Versatile, etc.
  $query = new CreateQuery('item_properties');
  $query->addField('item_id', 'INTEGER', 0, array('P'));
  $query->addField('property_id', 'INTEGER', 0, array('P'));

  // Ammunition, Heavy, Versatile, etc.
  $query = new CreateQuery('properties');
  $query->addField('id', 'INTEGER', 0, array('P', 'A'));
  $query->addField('name', 'TEXT', 32, array('N'));
  $query->addField('description', 'TEXT', 1024);
  $db->create($query);

  // Common, uncommon, rare, etc.
  $query = new CreateQuery('rarities');
  $query->addField('id', 'INTEGER', 0, array('P', 'A'));
  $query->addField('name', 'TEXT', 32, array('N'));
  $query->addField('description', 'TEXT', 1024);
  $db->create($query);
}

/******************************************************************************
 *
 *  Item.
 *
 ******************************************************************************/

/**
 * @param int $page
 * @return array
 */
function getItemPager($page = 1)
{
  GLOBAL $db;

  $query = new SelectQuery('items');
  $query->addField('id');
  $query->addField('name');
  $query->addField('item_type_id');
  $query->addField('value');
  $query->addField('attunement');
  $query->addField('description');
  $query->addOrderSimple('id');
  $query->addPager($page);

  return $db->select($query);
}

/**
 * @param int $item_id
 *
 * @return array|false
 */
function getItem($item_id)
{
  GLOBAL $db;

  $query = new SelectQuery('items');
  $query->addField('id');
  $query->addField('name');
  $query->addField('item_type_id');
  $query->addField('item_type_details');
  $query->addField('value');
  $query->addField('weight');
  $query->addField('rarity_id');
  $query->addField('attunement');
  $query->addField('attunement_requirements');
  $query->addField('artifact');
  $query->addField('description');
  $query->addField('source_id');
  $query->addField('source_location');
  $query->addConditionSimple('id', $item_id);
  return $db->selectObject($query);
}

function createItem($item)
{
  GLOBAL $db;

  $query = new InsertQuery('items');
  $query->addField('name', $item['name']);
  $query->addField('item_type_id', $item['item_type_id']);
  $query->addField('item_type_details', $item['item_type_details']);
  $query->addField('value', $item['value']);
  $query->addField('weight', $item['weight']);
  $query->addField('rarity_id', $item['rarity_id']);
  $query->addField('attunement', $item['attunement']);
  $query->addField('attunement_requirements', $item['attunement_requirements']);
  $query->addField('artifact', $item['artifact']);
  $query->addField('description', $item['description']);
  $query->addField('source_id', $item['source_id']);
  $query->addField('source_location', $item['source_location']);

  return $db->insert($query);
}

function updateItem($item)
{
  GLOBAL $db;

  $query = new UpdateQuery('items');
  $query->addField('name', $item['name']);
  $query->addField('item_type_id', $item['item_type_id']);
  $query->addField('item_type_details', $item['item_type_details']);
  $query->addField('value', $item['value']);
  $query->addField('weight', $item['weight']);
  $query->addField('rarity_id', $item['rarity_id']);
  $query->addField('attunement', $item['attunement']);
  $query->addField('attunement_requirements', $item['attunement_requirements']);
  $query->addField('artifact', $item['artifact']);
  $query->addField('description', $item['description']);
  $query->addField('source_id', $item['source_id']);
  $query->addField('source_location', $item['source_location']);
  $query->addConditionSimple('id', $item['id']);

  $db->update($query);
}

/**
 * @param int $item_id
 */
function deleteItem($item_id)
{
  GLOBAL $db;

  $query = new DeleteQuery('items');
  $query->addConditionSimple('id', $item_id);
  $db->delete($query);
}

/******************************************************************************
 *
 *  Item Type.
 *
 ******************************************************************************/

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

/******************************************************************************
 *
 *  Rarity.
 *
 ******************************************************************************/

/**
 * @param int $page
 *
 * @return array
 */
function getRarityPager($page = 1)
{
  GLOBAL $db;

  $query = new SelectQuery('rarities');
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

  $query = new UpdateQuery('rarities');
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

/******************************************************************************
 *
 *  Damage Type.
 *
 ******************************************************************************/

/**
 * @param int $page
 *
 * @return array|false
 */
function getDamageTypePager($page = 1)
{
  GLOBAL $db;

  $query = new SelectQuery('damage_types');
  $query->addField('id');
  $query->addField('name');
  $query->addField('code');
  $query->addField('description');
  $query->addOrderSimple('name');
  $query->addPager($page);

  return $db->select($query);
}

/**
 * @return array
 */
function getDamageTypeList()
{
  GLOBAL $db;

  $query = new SelectQuery('damage_types');
  $query->addField('id')->addField('name', 'value');

  return $db->selectList($query);
}

/**
 * @return array
 */
function getDamageTypeCodeList()
{
  GLOBAL $db;

  $query = new SelectQuery('damage_types');
  $query->addField('id')->addField('code', 'value');

  return $db->selectList($query);
}

/**
 * @param int $damage_type_id
 *
 * @return array|false
 */
function getDamageType($damage_type_id)
{
  GLOBAL $db;

  $query = new SelectQuery('damage_types');
  $query->addField('id');
  $query->addField('name');
  $query->addField('code');
  $query->addField('description');
  $query->addConditionSimple('id', $damage_type_id);
  return $db->selectObject($query);
}

/**
 * @param array $damage_type
 *
 * @return int
 */
function createDamageType($damage_type)
{
  GLOBAL $db;

  $query = new InsertQuery('damage_types');
  $query->addField('name', $damage_type['name']);
  $query->addField('code', $damage_type['code']);
  $query->addField('description', $damage_type['description']);

  return $db->insert($query);
}

/**
 * @param array $damage_type
 */
function updateDamageType($damage_type)
{
  GLOBAL $db;

  $query = new UpdateQuery('damage_types');
  $query->addField('name', $damage_type['name']);
  $query->addField('code', $damage_type['code']);
  $query->addField('description', $damage_type['description']);
  $query->addConditionSimple('id', $damage_type['id']);

  $db->update($query);
}

/**
 * @param int $damage_type_id
 */
function deleteDamageType($damage_type_id)
{
  GLOBAL $db;
  $query = new DeleteQuery('damage_types');
  $query->addConditionSimple('id', $damage_type_id);

  $db->delete($query);
}

/******************************************************************************
 *
 *  Property.
 *
 ******************************************************************************/

/**
 * @param int $page
 *
 * @return array
 */
function getPropertyPager($page = 1)
{
  GLOBAL $db;

  $query = new SelectQuery('properties');
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
function getPropertyList()
{
  GLOBAL $db;

  $query = new SelectQuery('properties');
  $query->addField('id')->addField('name', 'value');

  return $db->selectList($query);
}

/**
 * @param int $property_id
 *
 * @return array|false
 */
function getProperty($property_id)
{
  GLOBAL $db;

  $query = new SelectQuery('properties');
  $query->addField('id');
  $query->addField('name');
  $query->addField('description');
  $query->addConditionSimple('id', $property_id);

  return $db->selectObject($query);
}

/**
 * @param array $property
 *
 * @return int
 */
function createProperty($property)
{
  GLOBAL $db;

  $query = new InsertQuery('properties');
  $query->addField('name', $property['name']);
  $query->addField('description', $property['description']);

  return $db->insert($query);
}

/**
 * @param array $property
 */
function updateProperty($property)
{
  GLOBAL $db;

  $query = new UpdateQuery('properties');
  $query->addField('name', $property['name']);
  $query->addField('description', $property['description']);
  $query->addConditionSimple('id', $property['id']);

  $db->update($query);
}

/**
 * @param int $property_id
 */
function deleteProperty($property_id)
{
  GLOBAL $db;
  $query = new DeleteQuery('properties');
  $query->addConditionSimple('id', $property_id);

  $db->delete($query);
}
