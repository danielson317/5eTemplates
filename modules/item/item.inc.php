<?php

define('ITEM_WEAPON', 1);
define('ITEM_ARMOR', 2);
define('ITEM_GEAR', 3);
define('ITEM_BAG', 4);
define('ITEM_TOOL', 5);
define('ITEM_MOUNT', 6);
define('ITEM_TRADE', 7);
define('ITEM_FOOD', 8);
define('ITEM_SERVICE', 9);
define('ITEM_TRINKET', 10);

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
    $list[$category['id']] = str_repeat('-', $level) . $category['name'];
    if ($category['is_category'])
    {
      $list += itemCategoryList($category['id'], $level + 1);
    }
  }
  return $list;
}

function itemCategoryHierarchy($item_id)
{
  $output = '';
  $item = getItem($item_id);
  if ($item['parent_id'] && $item['parent_id'] > 0)
  {
    $output .= itemCategoryHierarchy($item['parent_id']) . ' >> ';
  }
  $output .= $item['name'];
  return $output;
}
