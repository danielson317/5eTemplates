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
  $query->addField('id', 'INTEGER', array('P', 'A'));
  $query->addField('code', 'TEXT', array('N'));
  $query->addField('name', 'TEXT', array('N'));
  $query->addField('description', 'TEXT');
  $db->create($query);
  
  // Armor, Gemstone, Wand, Weapon, etc.
  $query = new CreateQuery('item_types');
  $query->addField('id', 'INTEGER', array('P', 'A'));
  $query->addField('name', 'TEXT', array('N'));
  $query->addField('description', 'TEXT');
  $db->create($query);

  // Sword, Shield, Rome, Boots of butt-kicking, etc.
  $query = new CreateQuery('items');
  $query->addField('id', 'INTEGER', array('P', 'A'));
  $query->addField('name', 'TEXT', array('N'));
  $query->addField('item_type_id', 'INTEGER', array('N'), 0);
  $query->addField('item_type_details', 'TEXT');
  $query->addField('value', 'INTEGER', array('N'), 0);
  $query->addField('weight', 'INTEGER', array('N'), 0);
  $query->addField('rarity_id', 'INTEGER', array('N'), 0);
  $query->addField('attunement', 'INTEGER', array('N'), 0);
  $query->addField('attunement_requirements', 'TEXT');
  $query->addField('artifact', 'INTEGER', array('N'), 0);
  $query->addField('description', 'TEXT');
  $query->addField('source_id', 'INTEGER', array('N'), 0);
  $query->addField('source_location', 'INTEGER', array('N'), 0);

  $query->addField('damage_die_count', 'INTEGER', array('N'), 0);
  $query->addField('damage_die', 'INTEGER', array('N'), 0);
  $query->addField('damage_type_id', 'INTEGER', array('N'), 0);
  $query->addField('range_id', 'INTEGER', array('N'), 0);
  $query->addField('disadvantage_range_id', 'INTEGER', array('N'), 0);

  $query->addField('ac', 'INTEGER', array('N'), 0);
  $query->addField('strength', 'INTEGER', array('N'), 0);
  $db->create($query);

  // Ammunition, Heavy, Versatile, etc.
  $query = new CreateQuery('item_properties');
  $query->addField('item_id', 'INTEGER', array('P'));
  $query->addField('property_id', 'INTEGER', array('P'));

  // Ammunition, Heavy, Versatile, etc.
  $query = new CreateQuery('properties');
  $query->addField('id', 'INTEGER', array('P', 'A'));
  $query->addField('name', 'TEXT', array('N'));
  $query->addField('description', 'TEXT');
  $db->create($query);

  // Common, uncommon, rare, etc.
  $query = new CreateQuery('rarities');
  $query->addField('id', 'INTEGER', array('P', 'A'));
  $query->addField('name', 'TEXT', array('N'));
  $query->addField('description', 'TEXT');
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
  $query->addField('id')
    ->addField('name')
    ->addField('item_type_id')
    ->addField('value')
    ->addField('attunement')
    ->addField('description');
  $query->addOrderSimple('id');
  $query->addPager($page);

  $results = $db->select($query);
  if (!$results)
  {
    return array();
  }
  return $results;
}

function getItem($id)
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
  $query->addConditionSimple('id', $id);
  $results = $db->select($query);
  if (!$results)
  {
    return FALSE;
  }
  $result = array_shift($results);
  return $result;
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

function deleteItem($id)
{

}

/******************************************************************************
 *
 *  Item Type.
 *
 ******************************************************************************/

function getItemTypePager($page = 1)
{
  GLOBAL $db;

  $query = new SelectQuery('item_types');
  $query->addField('id');
  $query->addField('name');
  $query->addField('description');
  $query->addOrderSimple('id');
  $query->addPager($page);

  $results = $db->select($query);
  if (!$results)
  {
    return array();
  }
  return $results;
}

function getItemTypeList()
{
  GLOBAL $db;

  $query = new SelectQuery('item_types');
  $query->addField('id')->addField('name', 'value');

  return $db->selectList($query);
}


function getItemType($id)
{
  GLOBAL $db;

  $query = new SelectQuery('item_types');
  $query->addField('id');
  $query->addField('name');
  $query->addField('description');
  $query->addConditionSimple('id', $id);
  $results = $db->select($query);
  if (!$results)
  {
    return FALSE;
  }
  $result = array_shift($results);
  return $result;
}

function createItemType($item)
{
  GLOBAL $db;

  $query = new InsertQuery('item_types');
  $query->addField('name', $item['name']);
  $query->addField('description', $item['description']);

  return $db->insert($query);
}

function updateItemType($item)
{
  GLOBAL $db;

  $query = new UpdateQuery('item_types');
  $query->addField('name', $item['name']);
  $query->addField('description', $item['description']);
  $query->addConditionSimple('id', $item['id']);

  $db->update($query);
}

function deleteItemType($id)
{
  GLOBAL $db;
  $query = new DeleteQuery('item_types');
  $query->addConditionSimple('id', $id);

  $db->delete($query);
}

/******************************************************************************
 *
 *  Rarity.
 *
 ******************************************************************************/

function getRarityPager($page = 1)
{
  GLOBAL $db;

  $query = new SelectQuery('rarities');
  $query->addField('id');
  $query->addField('name');
  $query->addField('description');
  $query->addOrderSimple('id');
  $query->addPager($page);

  $results = $db->select($query);
  if (!$results)
  {
    return array();
  }
  return $results;
}

function getRarityList()
{
  GLOBAL $db;

  $query = new SelectQuery('rarities');
  $query->addField('id')->addField('name', 'value');

  return $db->selectList($query);
}


function getRarity($id)
{
  GLOBAL $db;

  $query = new SelectQuery('rarities');
  $query->addField('id');
  $query->addField('name');
  $query->addField('description');
  $query->addConditionSimple('id', $id);
  $results = $db->select($query);
  if (!$results)
  {
    return FALSE;
  }
  $result = array_shift($results);
  return $result;
}

function createRarity($item)
{
  GLOBAL $db;

  $query = new InsertQuery('rarities');
  $query->addField('name', $item['name']);
  $query->addField('description', $item['description']);

  return $db->insert($query);
}

function updateRarity($item)
{
  GLOBAL $db;

  $query = new UpdateQuery('rarities');
  $query->addField('name', $item['name']);
  $query->addField('description', $item['description']);
  $query->addConditionSimple('id', $item['id']);

  $db->update($query);
}

function deleteRarity($id)
{
  GLOBAL $db;
  $query = new DeleteQuery('rarities');
  $query->addConditionSimple('id', $id);

  $db->delete($query);
}

/******************************************************************************
 *
 *  Damage Type.
 *
 ******************************************************************************/

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

  $results = $db->select($query);
  if (!$results)
  {
    return array();
  }
  return $results;
}

function getDamageTypeList()
{
  GLOBAL $db;

  $query = new SelectQuery('damage_types');
  $query->addField('id')->addField('name', 'value');

  return $db->selectList($query);
}

function getDamageTypeCodeList()
{
  GLOBAL $db;

  $query = new SelectQuery('damage_types');
  $query->addField('id')->addField('code', 'value');

  return $db->selectList($query);
}

function getDamageType($id)
{
  GLOBAL $db;

  $query = new SelectQuery('damage_types');
  $query->addField('id');
  $query->addField('name');
  $query->addField('code');
  $query->addField('description');
  $query->addConditionSimple('id', $id);
  $results = $db->select($query);
  if (!$results)
  {
    return FALSE;
  }
  $result = array_shift($results);
  return $result;
}

function createDamageType($damage_type)
{
  GLOBAL $db;

  $query = new InsertQuery('damage_types');
  $query->addField('name', $damage_type['name']);
  $query->addField('code', $damage_type['code']);
  $query->addField('description', $damage_type['description']);

  return $db->insert($query);
}

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

function deleteDamageType($id)
{
  GLOBAL $db;
  $query = new DeleteQuery('damage_types');
  $query->addConditionSimple('id', $id);

  $db->delete($query);
}

/******************************************************************************
 *
 *  Property.
 *
 ******************************************************************************/

function getPropertyPager($page = 1)
{
  GLOBAL $db;

  $query = new SelectQuery('properties');
  $query->addField('id');
  $query->addField('name');
  $query->addField('description');
  $query->addOrderSimple('id');
  $query->addPager($page);

  $results = $db->select($query);
  if (!$results)
  {
    return array();
  }
  return $results;
}

function getPropertyList()
{
  GLOBAL $db;

  $query = new SelectQuery('properties');
  $query->addField('id')->addField('name', 'value');

  return $db->selectList($query);
}


function getProperty($id)
{
  GLOBAL $db;

  $query = new SelectQuery('properties');
  $query->addField('id');
  $query->addField('name');
  $query->addField('description');
  $query->addConditionSimple('id', $id);
  $results = $db->select($query);
  if (!$results)
  {
    return FALSE;
  }
  $result = array_shift($results);
  return $result;
}

function createProperty($item)
{
  GLOBAL $db;

  $query = new InsertQuery('properties');
  $query->addField('name', $item['name']);
  $query->addField('description', $item['description']);

  return $db->insert($query);
}

function updateProperty($item)
{
  GLOBAL $db;

  $query = new UpdateQuery('properties');
  $query->addField('name', $item['name']);
  $query->addField('description', $item['description']);
  $query->addConditionSimple('id', $item['id']);

  $db->update($query);
}

function deleteProperty($id)
{
  GLOBAL $db;
  $query = new DeleteQuery('properties');
  $query->addConditionSimple('id', $id);

  $db->delete($query);
}

