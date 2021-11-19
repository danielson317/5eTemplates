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

  // Join Weapon Table.
  $condition = new QueryCondition('id', 'i');
  $condition->setValueField('item_id', 'ia');
  $table = new QueryTable('items_armor', 'ia', QueryTable::LEFT_JOIN, $condition);
  $query->addTable($table);
  $query->addField('item_id', 'is_armor', 'ia');
  $query->addField('base_ac', 'base_ac', 'ia');
  $query->addField('dex_cap', 'dex_cap', 'ia');
  $query->addField('str_score', 'str_score', 'ia');
  $query->addField('stealth_disadvantage', 'stealth_disadvantage', 'ia');

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
