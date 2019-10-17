<?php
/***
 *
 * item.db.php
 *
 * items - any physical object that a creature may poses. Includes armor, weapons, tools, adventure gear, money, etc.
 * item_types - The hierarchical classification of the item that describes how game rules apply to it or groups
 *    the items by proficiencies and usages. "armor" => "light armor", "weapon", "tool", "adventure gear" => "holy symbol", etc.
 *
 *
 */

/**
 *
 */
function installItem()
{
  GLOBAL $db;

  $query = new CreateQuery('items');

  // All items.
  $query->addField('id', 'INTEGER', 0, array('P', 'A'));
  $query->addField('name', 'TEXT', 32, array('N'));
  $query->addField('parent_id', 'INTEGER', 0, array('N'), 0);
  $query->addField('is_category', 'BOOL', 0, array('N'), 0);
  $query->addField('value', 'INTEGER', 0, array('N'), 0);
  $query->addField('weight', 'INTEGER', 0, array('N'), 0);
  $query->addField('description', 'TEXT', 1024);
  $query->addField('source_id', 'INTEGER', 0, array('N'), 0);
  $query->addField('source_location', 'INTEGER', 0, array('N'), 0);

  // Magical items.
  $query->addField('magic', 'INTEGER', 0, array('N'), 0);
  $query->addField('rarity_id', 'INTEGER', 0, array('N'), 0);
  $query->addField('bonus', 'INTEGER', 0, array('N'), 0);
  $query->addField('attunement', 'INTEGER', 0, array('N'), 0);
  $query->addField('attunement_requirements', 'INTEGER', 0, array('N'), 0);

  // Weapons
  $query->addField('range_id', 'INTEGER', 0, array('N'), 0);
  $query->addField('max_range_id', 'INTEGER', 0, array('N'), 0);
  $query->addField('light', 'INTEGER', 0, array('N'), 0);
  $query->addField('finesse', 'INTEGER', 0, array('N'), 0);
  $query->addField('thrown', 'INTEGER', 0, array('N'), 0);
  $query->addField('ammunition', 'INTEGER', 0, array('N'), 0);
  $query->addField('loading', 'INTEGER', 0, array('N'), 0);
  $query->addField('heavy', 'INTEGER', 0, array('N'), 0);
  $query->addField('reach', 'INTEGER', 0, array('N'), 0);
  $query->addField('special', 'INTEGER', 0, array('N'), 0);
  $query->addField('two_handed', 'INTEGER', 0, array('N'), 0);

  // Armor
  $query->addField('base_ac', 'INTEGER', 0, array('N'), 0);
  $query->addField('dex_cap', 'INTEGER', 0, array('N'), 0);
  $query->addField('strength_requirement', 'INTEGER', 0, array('N'), 0);
  $query->addField('stealth_disadvantage', 'INTEGER', 0, array('N'), 0);
  $db->create($query);

  // Item damage many to many map.
  $query = new CreateQuery('item_damages');
  $query->addField('id', 'INTEGER', 0, array('P'));
  $query->addField('item_id', 'INTEGER', 0, array('N'));
  $query->addField('die_count', 'INTEGER', 0, array('N'), 0);
  $query->addField('die_id', 'INTEGER', 0, array('N'), 0);
  $query->addField('damage_type_id', 'INTEGER', 0, array('N'), 0);
  $query->addField('versatile', 'INTEGER', 0, array('N'), 0);
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
  $query->addField('value');
  $query->addField('item_type_id');
  $query->addField('description');
  $query->addOrderSimple('id');
  $query->addPager($page);

  return $db->select($query);
}

function getItemAutocompleteAjax()
{
  $response = getAjaxDefaultResponse();
  $term = getUrlText('term');

  GLOBAL $db;

  $query = new SelectQuery('items');
  $query->addField('id');
  $query->addField('name', 'value');
  $query->addField('name', 'label');

//  if ($key)
//  {
//    $query->addConditionSimple('id', $key);
//  }
//  else
//  {
    $query->addConditionSimple('name', $db->likeEscape($term) . '%', QueryCondition::COMPARE_LIKE);
    $query->addPager(1, PAGER_SIZE_MINIMUM);
//  }

  $response['data'] = $db->select($query);

  jsonResponseDie($response);
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

  // All items.
  $query->addField('id');
  $query->addField('name');
  $query->addField('item_type_id');
  $query->addField('value');
  $query->addField('weight');
  $query->addField('description');
  $query->addField('source_id');
  $query->addField('source_location');

  // Magic.
  $query->addField('magic');
  $query->addField('rarity_id');
  $query->addField('bonus');
  $query->addField('attunement');
  $query->addField('attunement_requirements');

  // Weapons.
  $query->addField('range_id');
  $query->addField('max_range_id');
  $query->addField('light');
  $query->addField('finesse');
  $query->addField('thrown');
  $query->addField('ammunition');
  $query->addField('loading');
  $query->addField('heavy');
  $query->addField('reach');
  $query->addField('special');
  $query->addField('two_handed');

  // Armor.
  $query->addField('base_ac');
  $query->addField('dex_cap');
  $query->addField('strength_requirement');
  $query->addField('stealth_disadvantage');

  $query->addConditionSimple('id', $item_id);
  return $db->selectObject($query);
}

function createItem($item)
{
  $item['magic'] = isset($_POST['magic']) ? 1 : 0;
  $item['attunement'] = isset($_POST['attunement']) ? 1 : 0;
  $item['light'] = isset($_POST['light']) ? 1 : 0;
  $item['finesse'] = isset($_POST['finesse']) ? 1 : 0;
  $item['thrown'] = isset($_POST['thrown']) ? 1 : 0;
  $item['ammunition'] = isset($_POST['ammunition']) ? 1 : 0;
  $item['loading'] = isset($_POST['loading']) ? 1 : 0;
  $item['heavy'] = isset($_POST['heavy']) ? 1 : 0;
  $item['reach'] = isset($_POST['reach']) ? 1 : 0;
  $item['special'] = isset($_POST['special']) ? 1 : 0;
  $item['two_handed'] = isset($_POST['two_handed']) ? 1 : 0;

  GLOBAL $db;
  $query = new InsertQuery('items');

  // All items.
  $query->addField('name', $item['name']);
  $query->addField('item_type_id', $item['item_type_id']);
  $query->addField('value', $item['value']);
  $query->addField('weight', $item['weight']);
  $query->addField('description', $item['description']);
  $query->addField('source_id', $item['source_id']);
  $query->addField('source_location', $item['source_location']);

  // Magic.
  $query->addField('magic', $item['magic']);
  $query->addField('rarity_id', $item['rarity_id']);
  $query->addField('bonus', $item['bonus']);
  $query->addField('attunement', $item['attunement']);
  $query->addField('attunement_requirements', $item['attunement_requirements']);

  // Weapons.
  $query->addField('range_id', $item['range_id']);
  $query->addField('max_range_id', $item['max_range_id']);
  $query->addField('light', $item['light']);
  $query->addField('finesse', $item['finesse']);
  $query->addField('thrown', $item['thrown']);
  $query->addField('ammunition', $item['ammunition']);
  $query->addField('loading', $item['loading']);
  $query->addField('heavy', $item['heavy']);
  $query->addField('reach', $item['reach']);
  $query->addField('special', $item['special']);
  $query->addField('two_handed', $item['two_handed']);

  // Armor.
  $query->addField('base_ac', $item['base_ac']);
  $query->addField('dex_cap', $item['dex_cap']);
  $query->addField('strength_requirement', $item['strength_requirement']);
  $query->addField('stealth_disadvantage', $item['stealth_disadvantage']);

  return $db->insert($query);
}

function updateItem($item)
{

  $item['magic'] = isset($_POST['magic']) ? 1 : 0;
  $item['attunement'] = isset($_POST['attunement']) ? 1 : 0;
  $item['light'] = isset($_POST['light']) ? 1 : 0;
  $item['finesse'] = isset($_POST['finesse']) ? 1 : 0;
  $item['thrown'] = isset($_POST['thrown']) ? 1 : 0;
  $item['ammunition'] = isset($_POST['ammunition']) ? 1 : 0;
  $item['loading'] = isset($_POST['loading']) ? 1 : 0;
  $item['heavy'] = isset($_POST['heavy']) ? 1 : 0;
  $item['reach'] = isset($_POST['reach']) ? 1 : 0;
  $item['special'] = isset($_POST['special']) ? 1 : 0;
  $item['two_handed'] = isset($_POST['two_handed']) ? 1 : 0;

  GLOBAL $db;
  $query = new UpdateQuery('items');

  // All items.
  $query->addField('name', $item['name']);
  $query->addField('item_type_id', $item['item_type_id']);
  $query->addField('value', $item['value']);
  $query->addField('weight', $item['weight']);
  $query->addField('description', $item['description']);
  $query->addField('source_id', $item['source_id']);
  $query->addField('source_location', $item['source_location']);

  // Magic.
  $query->addField('magic', $item['magic']);
  $query->addField('rarity_id', $item['rarity_id']);
  $query->addField('bonus', $item['bonus']);
  $query->addField('attunement', $item['attunement']);
  $query->addField('attunement_requirements', $item['attunement_requirements']);

  // Weapons.
  $query->addField('range_id', $item['range_id']);
  $query->addField('max_range_id', $item['max_range_id']);
  $query->addField('light', $item['light']);
  $query->addField('finesse', $item['finesse']);
  $query->addField('thrown', $item['thrown']);
  $query->addField('ammunition', $item['ammunition']);
  $query->addField('loading', $item['loading']);
  $query->addField('heavy', $item['heavy']);
  $query->addField('reach', $item['reach']);
  $query->addField('special', $item['special']);
  $query->addField('two_handed', $item['two_handed']);

  // Armor.
  $query->addField('base_ac', $item['base_ac']);
  $query->addField('dex_cap', $item['dex_cap']);
  $query->addField('strength_requirement', $item['strength_requirement']);
  $query->addField('stealth_disadvantage', $item['stealth_disadvantage']);

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
 *  Item Damage
 *
 ******************************************************************************/
/**
 * @param int $item_id
 *
 * @return array|false
 */
function getItemDamageList($item_id)
{
  GLOBAL $db;

  $query = new SelectQuery('item_damages');
  $query->addField('id');
  $query->addField('item_id');
  $query->addField('die_count');
  $query->addField('die_id');
  $query->addField('damage_type_id');
  $query->addField('versatile');
  $query->addConditionSimple('item_id', $item_id);
  $query->addOrderSimple('die_count', QueryOrder::DIRECTION_ASC);
  $results = $db->select($query);

  if (!$results)
  {
    return array();
  }
  return $results;
}

/**
 * @param int $item_damage_id
 *
 * @return array|mixed
 */
function getItemDamage($item_damage_id)
{
  GLOBAL $db;

  $query = new SelectQuery('item_damages');
  $query->addField('id');
  $query->addField('item_id');
  $query->addField('die_count');
  $query->addField('die_id');
  $query->addField('damage_type_id');
  $query->addField('versatile');
  $query->addConditionSimple('id', $item_damage_id);

  return $db->selectObject($query);
}

/**
 * @param array $item_damage
 */
function createItemDamage($item_damage)
{
  $item_damage['versatile'] = iis($item_damage, 'versatile', 0);

  GLOBAL $db;

  $query = new InsertQuery('item_damages');
  $query->addField('item_id', $item_damage['item_id']);
  $query->addField('die_count', $item_damage['die_count']);
  $query->addField('die_id', $item_damage['die_id']);
  $query->addField('damage_type_id', $item_damage['damage_type_id']);
  $query->addField('versatile', $item_damage['versatile']);
  $db->insert($query);
}

/**
 * @param array $item_damage
 */
function updateItemDamage($item_damage)
{
  $item_damage['versatile'] = iis($item_damage, 'versatile', 0);

  GLOBAL $db;

  $query = new UpdateQuery('item_damages');
  $query->addField('item_id', $item_damage['item_id']);
  $query->addField('die_count', $item_damage['die_count']);
  $query->addField('die_id', $item_damage['die_id']);
  $query->addField('damage_type_id', $item_damage['damage_type_id']);
  $query->addField('versatile', $item_damage['versatile']);
  $query->addConditionSimple('id', $item_damage['id']);
  $db->update($query);
}

/**
 * @param int $item_damage_id
 */
function deleteItemDamage($item_damage_id)
{
  GLOBAL $db;

  $query = new DeleteQuery('item_damages');
  $query->addConditionSimple('id', $item_damage_id);
  $db->delete($query);
}
