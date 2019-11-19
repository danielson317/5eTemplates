<?php

define('ITEM_WEAPON', 1000000);
define('ITEM_ARMOR', 1000001);
define('ITEM_GEAR', 1000002);
define('ITEM_BAG', 1000003);
define('ITEM_TOOL', 1000004);
define('ITEM_MOUNT', 1000005);
define('ITEM_TRADE', 1000006);
define('ITEM_FOOD', 1000008);
define('ITEM_SERVICE', 1000009);
define('ITEM_TRINKET', 1000010);

function getItemTypeList($key = FALSE)
{
  $items = array(
    ITEM_WEAPON => 'Weapon',
    ITEM_ARMOR => 'Armor',
    ITEM_GEAR => 'Adventuring Gear',
    ITEM_BAG => 'Container',
    ITEM_TOOL => 'Tool',
    ITEM_MOUNT => 'Mount or Vehicle',
    ITEM_TRADE => 'Trade Good',
    ITEM_FOOD => 'Food, Drink, and Lodging',
    ITEM_SERVICE => 'Service',
    ITEM_TRINKET => 'Trinket',
  );
  
  return getListItem($items, $key);
}

function itemCategoryList($parent_id = 0, $level = 0)
{
  $list = array();
  $categories = getItemCategoryList($parent_id);
  foreach($categories as $category)
  {
    $list[] = str_repeat('-', $level) . $category['name'];
    if ($category['is_category'])
    {
      $list[] += itemCategoryList($category['parent_id'], $level + 1);
    }
  }
  return $list;
}

