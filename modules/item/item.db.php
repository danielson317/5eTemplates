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

  // Items.
  $query = new CreateQuery('items');
  $query->addField('id', CreateQuery::TYPE_INTEGER, 0, array('P', 'A'));
  $query->addField('name', CreateQuery::TYPE_STRING, 32, array('N'));
  $query->addField('item_type_id', CreateQuery::TYPE_INTEGER, 0, array('N'));
  $query->addField('parent_id', CreateQuery::TYPE_INTEGER, 0, array('N'), 0); // -1 for category, 0 for no parent.
  $query->addField('description', CreateQuery::TYPE_STRING, 1024);
  $query->addField('source_id', CreateQuery::TYPE_INTEGER, 0, array('N'), 0);
  $query->addField('source_location', CreateQuery::TYPE_INTEGER, 0, array('N'), 0);
  $db->create($query);

  // Items Details.
  $query = new CreateQuery('item_details');
  $query->addField('id', CreateQuery::TYPE_INTEGER, 0, array('N'));
  $query->addField('value', CreateQuery::TYPE_INTEGER, 0, array('N'), 0);
  $query->addField('weight', CreateQuery::TYPE_INTEGER, 0, array('N'), 0);

  // Magical items.
  $query->addField('magic', CreateQuery::TYPE_INTEGER, 0, array('N'), 0);
  $query->addField('rarity_id', CreateQuery::TYPE_INTEGER, 0, array('N'), 0);
  $query->addField('bonus', CreateQuery::TYPE_INTEGER, 0, array('N'), 0);
  $query->addField('attunement', CreateQuery::TYPE_INTEGER, 0, array('N'), 0);
  $query->addField('attunement_requirements', CreateQuery::TYPE_INTEGER, 0, array('N'), 0);

  // Weapons
  $query->addField('range_id', CreateQuery::TYPE_INTEGER, 0, array('N'), 0);
  $query->addField('max_range_id', CreateQuery::TYPE_INTEGER, 0, array('N'), 0);
  $query->addField('light', CreateQuery::TYPE_INTEGER, 0, array('N'), 0);
  $query->addField('finesse', CreateQuery::TYPE_INTEGER, 0, array('N'), 0);
  $query->addField('thrown', CreateQuery::TYPE_INTEGER, 0, array('N'), 0);
  $query->addField('ammunition', CreateQuery::TYPE_INTEGER, 0, array('N'), 0);
  $query->addField('loading', CreateQuery::TYPE_INTEGER, 0, array('N'), 0);
  $query->addField('heavy', CreateQuery::TYPE_INTEGER, 0, array('N'), 0);
  $query->addField('reach', CreateQuery::TYPE_INTEGER, 0, array('N'), 0);
  $query->addField('special', CreateQuery::TYPE_INTEGER, 0, array('N'), 0);
  $query->addField('two_handed', CreateQuery::TYPE_INTEGER, 0, array('N'), 0);

  // Armor
  $query->addField('base_ac', CreateQuery::TYPE_INTEGER, 0, array('N'), 0);
  $query->addField('dex_cap', CreateQuery::TYPE_INTEGER, 0, array('N'), 0);
  $query->addField('strength_requirement', CreateQuery::TYPE_INTEGER, 0, array('N'), 0);
  $query->addField('stealth_disadvantage', CreateQuery::TYPE_INTEGER, 0, array('N'), 0);
  $db->create($query);

  // Item damage many to many map.
  $query = new CreateQuery('item_damages');
  $query->addField('id', CreateQuery::TYPE_INTEGER, 0, array('P'));
  $query->addField('item_id', CreateQuery::TYPE_INTEGER, 0, array('N'));
  $query->addField('die_count', CreateQuery::TYPE_INTEGER, 0, array('N'), 0);
  $query->addField('die_id', CreateQuery::TYPE_INTEGER, 0, array('N'), 0);
  $query->addField('damage_type_id', CreateQuery::TYPE_INTEGER, 0, array('N'), 0);
  $query->addField('versatile', CreateQuery::TYPE_INTEGER, 0, array('N'), 0);
  $db->create($query);

  $sources = array_flip(getSourceList());

  // Weapon categories.
  $item_types = array(
    array(
      'name' => 'Simple Melee Weapon',
      'parent_item_type_id' => $item_type_list['Weapon'],
      'description' => '',
      'source_id' => $sources['BR'],
    ),
    array(
      'name' => 'Simple Ranged Weapon',
      'parent_item_type_id' => $item_type_list['Weapon'],
      'description' => '',
      'source_id' => $sources['BR'],
    ),
    array(
      'name' => 'Martial Melee Weapon',
      'parent_item_type_id' => $item_type_list['Weapon'],
      'description' => '',
      'source_id' => $sources['BR'],
    ),
    array(
      'name' => 'Martial Ranged Weapon',
      'parent_item_type_id' => $item_type_list['Weapon'],
      'description' => '',
      'source_id' => $sources['BR'],
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
      'source_id' => $sources['BR'],
    ),
    array(
      'name' => 'Medium Armor',
      'parent_item_type_id' => $item_type_list['Armor'],
      'description' => '',
      'source_id' => $sources['BR'],
    ),
    array(
      'name' => 'Heavy Armor',
      'parent_item_type_id' => $item_type_list['Armor'],
      'description' => '',
      'source_id' => $sources['BR'],
    ),
    array(
      'name' => 'Shield',
      'parent_item_type_id' => $item_type_list['Armor'],
      'description' => '',
      'source_id' => $sources['BR'],
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
      'source_id' => $sources['BR'],
    ),
    array(
      'name' => 'Arcane Focus',
      'parent_item_type_id' => $item_type_list['Adventuring Gear'],
      'description' => '',
      'source_id' => $sources['BR'],
    ),
    array(
      'name' => 'Druidic Focus',
      'parent_item_type_id' => $item_type_list['Adventuring Gear'],
      'description' => '',
      'source_id' => $sources['BR'],
    ),
    array(
      'name' => 'Holy Symbol',
      'parent_item_type_id' => $item_type_list['Adventuring Gear'],
      'description' => '',
      'source_id' => $sources['BR'],
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
      'source_id' => $sources['BR'],
    ),
    array(
      'name' => 'Gaming Set',
      'parent_item_type_id' => $item_type_list['Tool'],
      'description' => '',
      'source_id' => $sources['BR'],
    ),
    array(
      'name' => 'Musical Instrument',
      'parent_item_type_id' => $item_type_list['Tool'],
      'description' => '',
      'source_id' => $sources['BR'],
    ),
  );
  foreach($item_types as $item_type)
  {
    createItemType($item_type);
  }
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
  GLOBAL $db;
  $query = new InsertQuery('items');

  // All items.
  $query->addField('name', $item['name']);
  $query->addField('parent_id', $item['parent_id']);
  $query->addField('description', $item['description']);
  $query->addField('source_id', $item['source_id']);
  $query->addField('source_location', $item['source_location']);

  return $db->insert($query);
}

function createItemDetails($item)
{
  GLOBAL $db;
  $query = new InsertQuery('item_details');

  // Magic.
  $query->addField('id', $item['id']);
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
