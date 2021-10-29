<?php
/***
 *
 * item.db.php
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
  $query->addField('value', CreateQuery::TYPE_INTEGER, 0, array('N'), 0);
  $query->addField('weight', CreateQuery::TYPE_INTEGER, 0, array('N'), 0);
  $query->addField('category_id', CreateQuery::TYPE_INTEGER, 0, array('N'), 0);
  $query->addField('description', CreateQuery::TYPE_STRING, 1024);
  $query->addField('source_id', CreateQuery::TYPE_INTEGER, 0, array('N'), 0);
  $query->addField('source_location', CreateQuery::TYPE_INTEGER, 0, array('N'), 0);
  $db->create($query);

  // Set the auto-increment to start at reserved max ID.
  $query = new UpdateQuery('SQLITE_SEQUENCE');
  $query->addField('seq', ItemCategory::RESERVED_MAX_ID);
  $query->addConditionSimple('name', 'items');
  $db->update($query);

  // Magic.
  $query = new CreateQuery('item_magics');
  $query->addField('item_id', CreateQuery::TYPE_INTEGER, 0, array('N', 'U'));
  $query->addField('rarity_id', CreateQuery::TYPE_INTEGER, 0, array('N'), 0);
  $query->addField('bonus', CreateQuery::TYPE_STRING, 128, array('N'), 0); // +1 to AC, +2 to hit and damage, Resistant to Fire Damage.
  $query->addField('attunement', CreateQuery::TYPE_BOOL, 0, array('N'), 0);
  $query->addField('attunement_requirements', CreateQuery::TYPE_STRING, 1024, array('N'), 0); // Only usable by druid, or evil.
  $db->create($query);

  // Weapon.
//  $query = new CreateQuery('item_weapons');
//  $query->addField('item_id', CreateQuery::TYPE_INTEGER, 0, array('N', 'U'));
//  $query->addField('range_id', CreateQuery::TYPE_INTEGER, 0, array('N'), 0);
//  $query->addField('max_range_id', CreateQuery::TYPE_INTEGER, 0, array('N'), 0);
//  $query->addField('light', CreateQuery::TYPE_BOOL, 0, array('N'), 0);
//  $query->addField('finesse', CreateQuery::TYPE_BOOL, 0, array('N'), 0);
//  $query->addField('thrown', CreateQuery::TYPE_BOOL, 0, array('N'), 0);
//  $query->addField('ammunition', CreateQuery::TYPE_BOOL, 0, array('N'), 0);
//  $query->addField('loading', CreateQuery::TYPE_BOOL, 0, array('N'), 0);
//  $query->addField('heavy', CreateQuery::TYPE_BOOL, 0, array('N'), 0);
//  $query->addField('reach', CreateQuery::TYPE_BOOL, 0, array('N'), 0);
//  $query->addField('special', CreateQuery::TYPE_BOOL, 0, array('N'), 0);
//  $query->addField('two_handed', CreateQuery::TYPE_BOOL, 0, array('N'), 0);
//  $db->create($query);

  // Armor
//  $query = new CreateQuery('item_armors');
//  $query->addField('item_id', CreateQuery::TYPE_INTEGER, 0, array('N', 'U'));
//  $query->addField('base_ac', CreateQuery::TYPE_INTEGER, 0, array('N'), 0);
//  $query->addField('dex_cap', CreateQuery::TYPE_INTEGER, 0, array('N'), 0);
//  $query->addField('strength_requirement', CreateQuery::TYPE_INTEGER, 0, array('N'), 0);
//  $query->addField('stealth_disadvantage', CreateQuery::TYPE_BOOL, 0, array('N'), 0);
//  $db->create($query);

  // Item damage - many to many map.
//  $query = new CreateQuery('item_damages');
//  $query->addField('id', CreateQuery::TYPE_INTEGER, 0, array('N', 'P', 'A'));
//  $query->addField('item_id', CreateQuery::TYPE_INTEGER, 0, array('N'));
//  $query->addField('die_count', CreateQuery::TYPE_INTEGER, 0, array('N'));
//  $query->addField('die_id', CreateQuery::TYPE_INTEGER, 0, array('N'));
//  $query->addField('damage_type_id', CreateQuery::TYPE_INTEGER, 0, array('N'));
//  $query->addField('versatile', CreateQuery::TYPE_BOOL, 0, array('N'));
//  $db->create($query);

  $sources = array_flip(getSourceList());

  // Top level categories.
  $items = array(
    array(
      'name' => 'Short Sword',
      'category_id' => ItemCategory::WEAPON_MARTIAL_MELEE,
      'value' => 1000, // in cp
      'weight' => 2, // in lb
      'description' => '',
      'source_id' => $sources['PHB'],
      'source_location' => 149,
    ),
//    array(
//      'name' => 'Armor',
//      'category_id' => 0,
//      'value' => 0,
//      'weight' => 0,
//      'description' => '',
//      'source_id' => $sources['BR'],
//      'source_location' => 46,
//    ),
//    array(
//      'name' => 'Adventuring Gear',
//      'category_id' => 0,
//      'value' => 0,
//      'weight' => 0,
//      'description' => '',
//      'source_id' => $sources['BR'],
//      'source_location' => 50,
//    ),
//    array(
//      'name' => 'Container',
//      'category_id' => 0,
//      'value' => 0,
//      'weight' => 0,
//      'description' => '',
//      'source_id' => $sources['BR'],
//      'source_location' => 52,
//    ),
//    array(
//      'name' => 'Tool',
//      'category_id' => 0,
//      'value' => 0,
//      'weight' => 0,
//      'description' => '',
//      'source_id' => $sources['BR'],
//      'source_location' => 52,
//    ),
//    array(
//      'name' => 'Mount or Vehicle',
//      'category_id' => 0,
//      'value' => 0,
//      'weight' => 0,
//      'description' => '',
//      'source_id' => $sources['BR'],
//      'source_location' => 54,
//    ),
//    array(
//      'name' => 'Trade Good',
//      'category_id' => 0,
//      'value' => 0,
//      'weight' => 0,
//      'description' => '',
//      'source_id' => $sources['BR'],
//      'source_location' => 54,
//    ),
//    array(
//      'name' => 'Food, Drink, and Lodging',
//      'category_id' => 0,
//      'value' => 0,
//      'weight' => 0,
//      'description' => '',
//      'source_id' => $sources['BR'],
//      'source_location' => 55,
//    ),
//    array(
//      'name' => 'Service',
//      'category_id' => 0,
//      'value' => 0,
//      'weight' => 0,
//      'description' => '',
//      'source_id' => $sources['BR'],
//      'source_location' => 56,
//    ),
//    array(
//      'name' => 'Trinket',
//      'category_id' => 0,
//      'value' => 0,
//      'weight' => 0,
//      'description' => '',
//      'source_id' => $sources['BR'],
//      'source_location' => 56,
//    ),
  );

  foreach ($items as $item)
  {
    createItem($item);
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
  $query->addField('weight');
  $query->addField('category_id');
  $query->addField('description');
  $query->addOrderSimple('category_id')->addOrderSimple('name');
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

function getItemList($key = FALSE)
{
  GLOBAL $db;
  $query = new SelectQuery('items');
  $query->addField('id');
  $query->addField('name', 'value');
  $list = $db->selectList($query);
  return getListItem($list, $key);
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
  $query->addField('value');
  $query->addField('weight');
  $query->addField('category_id');
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
  $query->addField('value', $item['value']);
  $query->addField('weight', $item['weight']);
  $query->addField('category_id', $item['category_id']);
  $query->addField('description', $item['description']);
  $query->addField('source_id', $item['source_id']);
  $query->addField('source_location', $item['source_location']);

  return $db->insert($query);
}

function updateItem($item)
{
  GLOBAL $db;
  $query = new UpdateQuery('items');

  // All items.
  $query->addField('name', $item['name']);
  $query->addField('category_id', $item['category_id']);
  $query->addField('value', $item['value']);
  $query->addField('weight', $item['weight']);
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
 *  Item Damage
 *
 ******************************************************************************/
function getItemMagic($item_id)
{
  GLOBAL $db;
  $query = new SelectQuery('item_magics');
  $query->addField('rarity_id');
  $query->addField('bonus');
  $query->addField('bonus_ability_id');
  $query->addField('attunement');
  $query->addField('attunement_requirements');
  return $db->selectObject($query);
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
