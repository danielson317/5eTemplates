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

  $query = new SelectQuery('items', 'i');
  $query->addField('id');
  $query->addField('name');
  $query->addField('value');
  $query->addField('weight');
  $query->addField('category_id');
  $query->addField('description');

  // Join Magic Item Table.
  $condition = new QueryCondition('id', 'i');
  $condition->setValueField('item_id', 'im');
  $table = new QueryTable('items_magic', 'im', QueryTable::LEFT_JOIN, $condition);
  $query->addTable($table);
  $query->addField('item_id', 'is_magic', 'im');
  $query->addField('rarity_id', 'rarity_id', 'im');

  // Join Weapon Table.
  $condition = new QueryCondition('id', 'i');
  $condition->setValueField('item_id', 'iw');
  $table = new QueryTable('items_weapon', 'iw', QueryTable::LEFT_JOIN, $condition);
  $query->addTable($table);
  $query->addField('item_id', 'is_weapon', 'iw');
  $query->addField('range_id', 'range_id', 'iw');
  $query->addField('max_range_id', 'max_range_id', 'iw');
  $query->addField('ammunition', 'ammunition', 'iw');
  $query->addField('finesse', 'finesse', 'iw');
  $query->addField('heavy', 'heavy', 'iw');
  $query->addField('light', 'light', 'iw');
  $query->addField('loading', 'loading', 'iw');
  $query->addField('reach', 'reach', 'iw');
  $query->addField('thrown', 'thrown', 'iw');
  $query->addField('two_handed', 'two_handed', 'iw');

  $query->addOrderSimple('category_id')->addOrderSimple('name');
  $query->addPager($page);
//  $query->setDebug('die');

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
