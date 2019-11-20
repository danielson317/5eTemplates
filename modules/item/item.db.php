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
  $query->addField('parent_id', CreateQuery::TYPE_INTEGER, 0, array('N'), 0); 
  $query->addField('is_category', CreateQuery::TYPE_INTEGER, 0, array('N'), 0);
  $query->addField('description', CreateQuery::TYPE_STRING, 1024);
  $query->addField('source_id', CreateQuery::TYPE_INTEGER, 0, array('N'), 0);
  $query->addField('source_location', CreateQuery::TYPE_INTEGER, 0, array('N'), 0);
  $db->create($query);

  // Magic.
  $query = new CreateQuery('item_magics');
  $query->addField('item_id', CreateQuery::TYPE_INTEGER, 0, array('N', 'U'));
  $query->addField('rarity_id', CreateQuery::TYPE_INTEGER, 0, array('N'), 0);
  $query->addField('bonus', CreateQuery::TYPE_INTEGER, 0, array('N'), 0);
  $query->addField('bonus_ability_id', CreateQuery::TYPE_INTEGER, 0, array('N'),
  $query->addField('attunement', CreateQuery::TYPE_BOOL, 0, array('N'), 0);
  $query->addField('attunement_requirements', CreateQuery::TYPE_STRING, 1024, array('N'), 0);
  $db->create($query);

  // Weapon.
  $query = new CreateQuery('item_weapons');
  $query->addField('item_id', CreateQuery::TYPE_INTEGER, 0, array('N', 'U'));
  $query->addField('range_id', CreateQuery::TYPE_INTEGER, 0, array('N'), 0);
  $query->addField('max_range_id', CreateQuery::TYPE_INTEGER, 0, array('N'), 0);
  $query->addField('light', CreateQuery::TYPE_BOOL, 0, array('N'), 0);
  $query->addField('finesse', CreateQuery::TYPE_BOOL, 0, array('N'), 0);
  $query->addField('thrown', CreateQuery::TYPE_BOOL, 0, array('N'), 0);
  $query->addField('ammunition', CreateQuery::TYPE_BOOL, 0, array('N'), 0);
  $query->addField('loading', CreateQuery::TYPE_BOOL, 0, array('N'), 0);
  $query->addField('heavy', CreateQuery::TYPE_BOOL, 0, array('N'), 0);
  $query->addField('reach', CreateQuery::TYPE_BOOL, 0, array('N'), 0);
  $query->addField('special', CreateQuery::TYPE_BOOL, 0, array('N'), 0);
  $query->addField('two_handed', CreateQuery::TYPE_BOOL, 0, array('N'), 0);
  $db->create($query);

  // Armor
  $query = new CreateQuery('item_armors');
  $query->addField('item_id', CreateQuery::TYPE_INTEGER, 0, array('N', 'U'));
  $query->addField('base_ac', CreateQuery::TYPE_INTEGER, 0, array('N'), 0);
  $query->addField('dex_cap', CreateQuery::TYPE_INTEGER, 0, array('N'), 0);
  $query->addField('strength_requirement', CreateQuery::TYPE_INTEGER, 0, array('N'), 0);
  $query->addField('stealth_disadvantage', CreateQuery::TYPE_BOOL, 0, array('N'), 0);
  $db->create($query);

  // Item damage many to many map.
  $query = new CreateQuery('item_damages');
  $query->addField('id', CreateQuery::TYPE_INTEGER, 0, array('N', 'P', 'A'));
  $query->addField('item_id', CreateQuery::TYPE_INTEGER, 0, array('N'));
  $query->addField('die_count', CreateQuery::TYPE_INTEGER, 0, array('N'));
  $query->addField('die_id', CreateQuery::TYPE_INTEGER, 0, array('N'));
  $query->addField('damage_type_id', CreateQuery::TYPE_INTEGER, 0, array('N'));
  $query->addField('versatile', CreateQuery::TYPE_BOOL, 0, array('N'));
  $db->create($query);

  $sources = array_flip(getSourceList());

  // Top level categories.
  $items = array(
    array(
      'id' => ITEM_WEAPON,
      'name' => 'Weapon',
      'parent_id' => 0,
      'is_category' => 1,
      'value' => 0,
      'weight' => 0,
      'description' => '',
      'source_id' => $sources['BR'],
      'source_location' => 48,
    ),
    array(
      'id' => ITEM_ARMOR,
      'name' => 'Armor',
      'parent_id' => 0,
      'is_category' => 1,
      'value' => 0,
      'weight' => 0,
      'description' => '',
      'source_id' => $sources['BR'],
      'source_location' => 46,
    ),
    array(
      'id' => ITEM_GEAR,
      'name' => 'Adventuring Gear',
      'parent_id' => 0,
      'is_category' => 1,
      'value' => 0,
      'weight' => 0,
      'description' => '',
      'source_id' => $sources['BR'],
      'source_location' => 50,
    ),
    array(
      'id' => ITEM_BAG,
      'name' => 'Container',
      'parent_id' => 0,
      'is_category' => 1,
      'value' => 0,
      'weight' => 0,
      'description' => '',
      'source_id' => $sources['BR'],
      'source_location' => 52,
    ),
    array(
      'id' => ITEM_TOOL,
      'name' => 'Tool',
      'parent_id' => 0,
      'is_category' => 1,
      'value' => 0,
      'weight' => 0,
      'description' => '',
      'source_id' => $sources['BR'],
      'source_location' => 52,
    ),
    array(
      'id' => ITEM_MOUNT,
      'name' => 'Mount or Vehicle',
      'parent_id' => 0,
      'is_category' => 1,
      'value' => 0,
      'weight' => 0,
      'description' => '',
      'source_id' => $sources['BR'],
      'source_location' => 54,
    ),
    array(
      'id' => ITEM_TRADE,
      'name' => 'Trade Good',
      'parent_id' => 0,
      'is_category' => 1,
      'value' => 0,
      'weight' => 0,
      'description' => '',
      'source_id' => $sources['BR'],
      'source_location' => 54,
    ),
    array(
      'id' => ITEM_FOOD,
      'name' => 'Food, Drink, and Lodging',
      'parent_id' => 0,
      'is_category' => 1,
      'value' => 0,
      'weight' => 0,
      'description' => '',
      'source_id' => $sources['BR'],
      'source_location' => 55,
    ),
    array(
      'id' => ITEM_SERVICE,
      'name' => 'Service',
      'parent_id' => 0,
      'is_category' => 1,
      'value' => 0,
      'weight' => 0,
      'description' => '',
      'source_id' => $sources['BR'],
      'source_location' => 56,
    ),
    array(
      'id' => ITEM_TRINKET,
      'name' => 'Trinket',
      'parent_id' => 0,
      'is_category' => 1,
      'value' => 0,
      'weight' => 0,
      'description' => '',
      'source_id' => $sources['BR'],
      'source_location' => 56,
    ),
  );

  foreach ($items as $item)
  {
    createItem($item, TRUE);
  }

  // Weapon categories.
  $items = array(
    array(
      'name' => 'Simple Melee Weapon',
      'parent_id' => ITEM_WEAPON,
      'is_category' => 1,
      'value' => 0,
      'weight' => 0,
      'description' => '',
      'source_id' => $sources['BR'],
      'source_location' => 0
    ),
    array(
      'name' => 'Simple Ranged Weapon',
      'parent_id' => ITEM_WEAPON,
      'is_category' => 1,
      'value' => 0,
      'weight' => 0,
      'description' => '',
      'source_id' => $sources['BR'],
      'source_location' => 0
    ),
    array(
      'name' => 'Martial Melee Weapon',
      'parent_id' => ITEM_WEAPON,
      'is_category' => 1,
      'value' => 0,
      'weight' => 0,
      'description' => '',
      'source_id' => $sources['BR'],
      'source_location' => 0
    ),
    array(
      'name' => 'Martial Ranged Weapon',
      'parent_id' => ITEM_WEAPON,
      'is_category' => 1,
      'value' => 0,
      'weight' => 0,
      'description' => '',
      'source_id' => $sources['BR'],
      'source_location' => 0
    ),
  );
  foreach($items as $item_type)
  {
    createItem($item_type);
  }

  // Armor Categories.
  $item = array(
    array(
      'name' => 'Light Armor',
      'parent_id' => ITEM_ARMOR,
      'is_category' => 1,
      'value' => 0,
      'weight' => 0,
      'description' => '',
      'source_id' => $sources['BR'],
      'source_location' => 0,
    ),
    array(
      'name' => 'Medium Armor',
      'parent_id' => ITEM_ARMOR,
      'is_category' => 1,
      'value' => 0,
      'weight' => 0,
      'description' => '',
      'source_id' => $sources['BR'],
      'source_location' => 0,
    ),
    array(
      'name' => 'Heavy Armor',
      'parent_id' => ITEM_ARMOR,
      'is_category' => 1,
      'value' => 0,
      'weight' => 0,
      'description' => '',
      'source_id' => $sources['BR'],
      'source_location' => 0,
    ),
    array(
      'name' => 'Shield',
      'parent_id' => ITEM_ARMOR,
      'is_category' => 1,
      'value' => 0,
      'weight' => 0,
      'description' => '',
      'source_id' => $sources['BR'],
      'source_location' => 0,
    ),
  );
  foreach($item as $item)
  {
    createItem($item);
  }

  // Gear Categories.
  $item = array(
    array(
      'name' => 'Ammunition',
      'parent_id' => ITEM_GEAR,
      'is_category' => 1,
      'value' => 0,
      'weight' => 0,
      'description' => '',
      'source_id' => $sources['BR'],
      'source_location' => 50,
    ),
    array(
      'name' => 'Arcane focus',
      'parent_id' => ITEM_GEAR,
      'is_category' => 1,
      'value' => 0,
      'weight' => 0,
      'description' => '',
      'source_id' => $sources['BR'],
      'source_location' => 50,
    ),
    array(
      'name' => 'Druidic focus',
      'parent_id' => ITEM_GEAR,
      'is_category' => 1,
      'value' => 0,
      'weight' => 0,
      'description' => '',
      'source_id' => $sources['BR'],
      'source_location' => 50,
    ),
    array(
      'name' => 'Holy symbol',
      'parent_id' => ITEM_GEAR,
      'is_category' => 1,
      'value' => 0,
      'weight' => 0,
      'description' => '',
      'source_id' => $sources['BR'],
      'source_location' => 50,
    ),
  );
 
  foreach($item as $item)
  {
    createItem($item);
  }

  $items = array(
    array(
      'name' => 'Artisan\'s tools',
      'parent_id' => ITEM_TOOL,
      'is_category' => 1,
      'value' => 0,
      'weight' => 0,
      'description' => '',
      'source_id' => $sources['BR'],
      'source_location' => 52,
    ),
    array(
      'name' => 'Gaming Kit',
      'parent_id' => ITEM_GEAR,
      'is_category' => 1,
      'value' => 0,
      'weight' => 0,
      'description' => '',
      'source_id' => $sources['BR'],
      'source_location' => 52,
    ),
    array(
      'name' => 'Musical instrument',
      'parent_id' => ITEM_GEAR,
      'is_category' => 1,
      'value' => 0,
      'weight' => 0,
      'description' => '',
      'source_id' => $sources['BR'],
      'source_location' => 52,
    ),
  );
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
  $query->addField('parent_id');
  $query->addField('description');
  $query->addOrderSimple('name');
  $query->addConditionSimple('is_category', 0);
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

function getItemCategoryList($parent_id = FALSE)
{
  GLOBAL $db;
  
  $query = new SelectQuery('items');
  $query->addField('id');
  $query->addField('name');
  $query->addField('value');
  $query->addField('weight');
  $query->addField('parent_id');
  $query->addField('is_category');
  $query->addField('description');
  $query->addConditionSimple('is_category', 1);
  
  if ($parent_id !== FALSE)
  {
    $query->addConditionSimple('parent_id', $parent_id);
  }

  return $db->select($query);
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
  $query->addField('is_category');
  $query->addField('parent_id');
  $query->addField('description');
  $query->addField('source_id');
  $query->addField('source_location');

  $query->addConditionSimple('id', $item_id);

  return $db->selectObject($query);
}

function createItem($item, $with_id = FALSE)
{
  GLOBAL $db;
  $query = new InsertQuery('items');

  // All items.
  if ($with_id)
  {
    $query->addField('id', $item['id']);
  }
  $query->addField('name', $item['name']);
  $query->addField('value', $item['value']);
  $query->addField('weight', $item['weight']);
  $query->addField('is_category', iis($item, 'is_category', 0));
  $query->addField('parent_id', $item['parent_id']);
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
  $query->addField('parent_id', $item['parent_id']);
  $query->addField('is_category', iis($item, 'is_category', 0));
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
