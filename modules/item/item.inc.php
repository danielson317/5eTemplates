<?php

class ItemCategory
{
  const RESERVED_MAX_ID = 10000;

  // Weapons
  const WEAPON = 100;
  const WEAPON_SIMPLE_MELEE = 101;
  const WEAPON_SIMPLE_RANGED = 102;
  const WEAPON_MARTIAL_MELEE= 103;
  const WEAPON_MARTIAL_RANGED= 104;

  // Armor
  const ARMOR = 200;
  const ARMOR_LIGHT = 201;
  const ARMOR_MEDIUM = 202;
  const ARMOR_HEAVY = 203;
  const ARMOR_SHIELD = 204;

  // Gear
  const GEAR = 300;
  const GEAR_AMMUNITION = 301;
  const GEAR_ARCANE_FOCUS = 302;
  const GEAR_DRUIDIC_FOCUS = 303;
  const GEAR_HOLY_SYMBOL = 304;

  // Containers
  const CONTAINER = 4;

  // Tools
  const TOOL = 500;
  const TOOL_ARTISAN = 501;
  const TOOL_GAMING = 502;
  const TOOL_INSTRUMENT = 503;

  // Mounts & Vehicles
  const MOUNT = 600;

  // Trade Goods
  const TRADE = 700;

  // Food
  const CONSUMABLE = 800;
  const CONSUMABLE_FOOD = 801;
  const CONSUMABLE_DRINK = 802;
  const CONSUMABLE_POTION = 803;

  // Services
  const SERVICE = 900;
  const SERVICE_TRANSPORT = 901;
  const SERVICE_LODGING = 902;
  const SERVICE_HIRELING = 903;
  const SERVICE_TAXES = 904; // Tolls, Cover Charge, Property, Even illigitimateEtc
  const SERVICE_PROFESSIONAL = 905; // Any skilled artisen service.

  // Trinkets
  const TRINKET = 1000;

  // Treasure
  const TREASURE = 1100;
  const TREASURE_GEMSTONE = 1101;
  const TREASURE_ART = 1102;

  public static function getList($key = FALSE)
  {
    $items = array(
      ItemCategory::WEAPON => 'Weapon',
        ItemCategory::WEAPON_SIMPLE_MELEE => 'Simple Melee Weapon',
        ItemCategory::WEAPON_SIMPLE_RANGED => 'Simple Ranged Weapon',
        ItemCategory::WEAPON_MARTIAL_MELEE => 'Simple Martial Weapon',
        ItemCategory::WEAPON_MARTIAL_RANGED => 'Simple Ranged Weapon',
      ItemCategory::ARMOR => 'Armor',
        ItemCategory::ARMOR_LIGHT => 'Light Armor',
        ItemCategory::ARMOR_MEDIUM => 'Medium Armor',
        ItemCategory::ARMOR_HEAVY => 'Heavy Armor',
        ItemCategory::ARMOR_SHIELD => 'Shield',
      ItemCategory::GEAR => 'Adventuring Gear',
        ItemCategory::GEAR_AMMUNITION => 'Ammunition',
        ItemCategory::GEAR_ARCANE_FOCUS => 'Arcane Focus',
        ItemCategory::GEAR_DRUIDIC_FOCUS => 'Druidic Focus',
        ItemCategory::GEAR_HOLY_SYMBOL => 'Holy Symbol',
      ItemCategory::CONTAINER => 'Container',
      ItemCategory::TOOL => 'Tool',
        ItemCategory::TOOL_ARTISAN => 'Artisan Tool',
        ItemCategory::TOOL_GAMING => 'Gaming Set',
        ItemCategory::TOOL_INSTRUMENT => 'Musical Instrument',
      ItemCategory::MOUNT => 'Mount or Vehicle',
      ItemCategory::TRADE => 'Trade Good',
      ItemCategory::CONSUMABLE => 'Consumable',
      ItemCategory::SERVICE => 'Service',
      ItemCategory::TRINKET => 'Trinket',
      ItemCategory::TREASURE => 'Treasure',
    );

    return getListItem($items, $key);
  }

  public static function getHierarchyList($key = FALSE)
  {
    $items = array(
      ItemCategory::WEAPON => 'Weapon',
        ItemCategory::WEAPON_SIMPLE_MELEE => '--Simple Melee Weapon',
        ItemCategory::WEAPON_SIMPLE_RANGED => '--Simple Ranged Weapon',
        ItemCategory::WEAPON_MARTIAL_MELEE => '--Simple Martial Weapon',
        ItemCategory::WEAPON_MARTIAL_RANGED => '--Simple Ranged Weapon',
      ItemCategory::ARMOR => 'Armor',
        ItemCategory::ARMOR_LIGHT => '--Light Armor',
        ItemCategory::ARMOR_MEDIUM => '--Medium Armor',
        ItemCategory::ARMOR_HEAVY => '--Heavy Armor',
        ItemCategory::ARMOR_SHIELD => '--Shield',
      ItemCategory::GEAR => 'Adventuring Gear',
        ItemCategory::GEAR_AMMUNITION => '--Ammunition',
        ItemCategory::GEAR_ARCANE_FOCUS => '--Arcane Focus',
        ItemCategory::GEAR_DRUIDIC_FOCUS => '--Druidic Focus',
        ItemCategory::GEAR_HOLY_SYMBOL => '--Holy Symbol',
      ItemCategory::CONTAINER => 'Container',
      ItemCategory::TOOL => 'Tool',
        ItemCategory::TOOL_ARTISAN => '--Artisan Tool',
        ItemCategory::TOOL_GAMING => '--Gaming Set',
        ItemCategory::TOOL_INSTRUMENT => '--Musical Instrument',
      ItemCategory::MOUNT => 'Mount or Vehicle',
      ItemCategory::TRADE => 'Trade Good',
      ItemCategory::CONSUMABLE => 'Consumable',
      ItemCategory::SERVICE => 'Service',
      ItemCategory::TRINKET => 'Trinket',
      ItemCategory::TREASURE => 'Treasure',
    );

    return getListItem($items, $key);
  }

  public static function isWeapon($category_id)
  {
    return ($category_id >= 100 && $category_id < 200);
  }
}

class ItemRarity
{
  const COMMON = 1;
  const UNCOMMON = 2;
  const RARE = 3;
  const VERY_RARE = 4;
  const LEGENDARY = 5;
  const ARTIFACT = 6;

  public static function getList($key = FALSE)
  {
    $list = array(
      ItemRarity::COMMON => 'Common',
      ItemRarity::UNCOMMON => 'Uncommon',
      ItemRarity::RARE => 'Rare',
      ItemRarity::VERY_RARE => 'Very Rare',
      ItemRarity::LEGENDARY => 'Legendary',
      ItemRarity::ARTIFACT => 'Artifact',
    );

    return getListItem($list, $key);
  }
}

/**
 * @param int $cp
 * @return string
 */
function itemFormatCost(int $cp)
{
  $magnitudes = array('CP', 'SP', 'GP');

  $top = $cp;
  $count = 0;
  do
  {
    if (($top % 10) !== 0)
    {
      break;
    }
    $top = $top / 10;
    $count++;
  } while ($top > 10 && $count < 2);

  return number_format($top) . ' ' . $magnitudes[$count];
}

function itemWeightFormat(int $pounds)
{
  $unit = 'lb';
  if ($pounds > 1)
  {
    $unit .= 's';
  }
  return $pounds . ' ' . $unit;
}

function itemFormatWeaponProperties($item)
{
  $properties = '';
  if ($item['range_id'])
  {
    if (((int)$item['range_id'] === SpellRange::TOUCH) || $item['thrown'])
    {
      $properties .= 'Melee ';
    }
    else
    {
      $properties .= 'Ranged (' . SpellRange::getList($item['range_id']);
      if ($item['max_range_id'])
      {
        $properties .= '/' . SpellRange::getList($item['max_range_id']);
      }
      $properties .= ') ';
    }
  }

  if ($item['ammunition'])
  {
    $properties .= ' Ammunition';
  }
  if ($item['finesse'])
  {
    $properties .= ' Finesse';
  }
  if ($item['heavy'])
  {
    $properties .= ' Heavy';
  }
  if ($item['light'])
  {
    $properties .= ' Light';
  }
  if ($item['loading'])
  {
    $properties .= ' Loading';
  }
  if ($item['reach'])
  {
    $properties .= ' Reach';
  }
  if ($item['thrown'])
  {
    $properties .= 'Thrown (' . SpellRange::getList($item['range_id']);
    if ($item['max_range_id'])
    {
      $properties .= '/' . SpellRange::getList($item['max_range_id']);
    }
    $properties .= ') ';
  }
  if ($item['two_handed'])
  {
    $properties .= ' Two Handed';
  }

  return $properties;
}
