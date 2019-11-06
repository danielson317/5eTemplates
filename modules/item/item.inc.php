<?php

function getItemTypeList($key = FALSE)
{
  $items = array(
    'weapon' => array(
      'name' => 'Weapon',
      'description' => '',
    ),
    'armor' => array(
      'name' => 'Armor',
      'description' => '',
    ),
    'gear' => array(
      'name' => 'Adventuring Gear',
      'description' => '',
    ),
    'bag' => array(
      'name' => 'Container',
      'description' => '',
    ),
    'tool' => array(
      'name' => 'Tool',
      'description' => '',
    ),
    'mount' => array(
      'name' => 'Mount or Vehicle',
      'description' => '',
    ),
    'trade' => array(
      'name' => 'Trade Good',
      'description' => '',
    ),
    'food' => array(
      'name' => 'Food, Drink, and Lodging',
      'description' => '',
    ),
    'service' => array(
      'name' => 'Service',
      'description' => '',
    ),
    'trinket' => array(
      'name' => 'Trinket',
      'description' => '',
    ),
  );
  
  return getListItem($items, $key);
}
