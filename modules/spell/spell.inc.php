<?php

function printSpellCard($spell)
{
  extract($spell);

  ob_start();

  include ROOT_PATH . '/themes/default/templates/spell.tpl.php';

  return ob_get_clean();
}

class SpellLevel
{
  const FIRST = 1;
  const SECOND = 2;
  const THIRD = 3;
  const FOURTH = 4;
  const FIFTH = 5;
  const SIXTH = 6;
  const SEVENTH = 7;
  const EIGHTH = 8;
  const NINTH = 9;
  const CANTRIP = 10;
  const RACE_SKILL = 11;
  const CLASS_SKILL = 12;

  public static function getList($key = FALSE)
  {
    $list = array(
      SpellLevel::CANTRIP => 'Cantrip',
      SpellLevel::FIRST => '1st',
      SpellLevel::SECOND => '2nd',
      SpellLevel::THIRD => '3rd',
      SpellLevel::FOURTH => '4th',
      SpellLevel::FIFTH => '5th',
      SpellLevel::SIXTH => '6th',
      SpellLevel::SEVENTH => '7th',
      SpellLevel::EIGHTH => '8th',
      SpellLevel::NINTH => '9th',
      SpellLevel::RACE_SKILL => 'Racial Skill',
      SpellLevel::CLASS_SKILL => 'Class Skill',
    );

    return getListItem($list, $key);
  }
}

class SpellSpeed
{
  const INSTANT = 1;
  const REACTION = 2;
  const BONUS_ACTION = 3;
  const ACTION = 6;
  const MINUTE = 60;
  const RITUAL = 600; // 60 * 10
  const SHORT_REST = 3600; // 60 * 60
  const HOUR_2 = 7200; // 3600 * 2
  const LONG_REST = 28800; // 3600 * 8
  const DAY = 86400; // 60 * 60 * 24

  public static function getList($key = FALSE)
  {
    $list = array(
      SpellSpeed::INSTANT => 'Instant',
      SpellSpeed::REACTION => 'Reaction',
      SpellSpeed::BONUS_ACTION => 'Bonus Action',
      SpellSpeed::ACTION => 'Action',
      SpellSpeed::MINUTE => '1 Minute',
      SpellSpeed::RITUAL => 'Ritual (10 Min)',
      SpellSpeed::SHORT_REST => 'Short Rest (1 Hour)',
      SpellSpeed::HOUR_2 => '2 Hours',
      SpellSpeed::LONG_REST => 'Long Rest (8 hours)',
      SpellSpeed::DAY => '1 Day',
    );
    return getListItem($list, $key);
  }
}

class SpellDuration
{
  const FOREVER = 1;
  const END_TARGET_TURN = 3;
  const END_CASTER_TURN = 6;
  const MIN = 60;
  const MINUTES_10 = 600; // 60 * 10
  const HOUR = 3600; // 60 * 60
  const HOUR_2 = 7200; // 3600 * 2
  const HOURS_8 = 28800; // 3600 * 8
  const DAY = 86400;
  const DAY_7 = 604800;
  const DAY_10 = 864000;
  const DAY_30 = 2592000;

  public static function getList($key = FALSE)
  {
    $list = array(
      SpellDuration::FOREVER => 'Instant',
      SpellDuration::END_TARGET_TURN => 'End of Target\'s Next Turn',
      SpellDuration::END_CASTER_TURN => 'End of Caster\'s Next Turn',
      SpellDuration::MIN => '1 Minute',
      SpellDuration::MINUTES_10 => '10 Min',
      SpellDuration::HOUR => '1 Hour',
      SpellDuration::HOUR_2 => '2 Hours',
      SpellDuration::HOURS_8 => '8 hours',
      SpellDuration::DAY => '1 Day',
      SpellDuration::DAY_7 => '7 Days',
      SpellDuration::DAY_10 => '10 Days',
      SpellDuration::DAY_30 => '30 Day2',
    );
    return getListItem($list, $key);
  }
}

class SpellRange
{
  const SELF = 1;
  const UNLIMITED = 3;
  const SEE = 4;
  const TOUCH = 5;
  const PLANE_SAME = 6;
  const PLANE_OTHER = 7;
  const FEET_10 = 10;
  const FEET_15 = 15;
  const FEET_20 = 20;
  const FEET_30 = 30;
  const FEET_60 = 60;
  const FEET_90 = 90;
  const FEET_100 = 100;
  const FEET_120 = 120;
  const FEET_150 = 150;
  const FEET_200 = 200;

  public  static function getList($key = FALSE)
  {
    $list = array(
      SpellRange::SELF => 'Self',
      SpellRange::UNLIMITED => 'Anywhere',
      SpellRange::SEE => 'See',
      SpellRange::TOUCH => 'Touch/Reach',
      SpellRange::PLANE_SAME => 'In the Same Plane',
      SpellRange::PLANE_OTHER => 'In a Different Plane',
      SpellRange::FEET_10 => '10 Feet',
      SpellRange::FEET_15 => '15 Feet',
      SpellRange::FEET_20 => '20 Feet',
      SpellRange::FEET_30 => '30 Feet',
      SpellRange::FEET_60 => '60 Feet',
      SpellRange::FEET_90 => '90 Feet',
      SpellRange::FEET_100 => '100 Feet',
      SpellRange::FEET_120 => '120 Feet',
      SpellRange::FEET_150 => '150 Feet',
      SpellRange::FEET_200 => '200 Feet',
    );

    return getListItem($list, $key);
  }
}

class SpellSchool
{
  const ABJURATION = 1;
  const CONJURATION = 2;
  const DIVINATION = 3;
  const ENCHANTMENT = 4;
  const EVOCATION = 5;
  const ILLUSION = 6;
  const NECROMANCY = 7;
  const TRANSMUTATION = 8;

  public static function getList($key = FALSE)
  {
    $list = array(
      SpellSchool::ABJURATION => 'Abjuration',
      SpellSchool::CONJURATION => 'Conjuration',
      SpellSchool::DIVINATION => 'Divination',
      SpellSchool::ENCHANTMENT => 'Enchantment',
      SpellSchool::EVOCATION => 'Evocation',
      SpellSchool::ILLUSION => 'Illusion',
      SpellSchool::NECROMANCY => 'Necromancy',
      SpellSchool::TRANSMUTATION => 'Transmutation',
    );
    return getListItem($list, $key);
  }
}

class SpellAOE
{
  const CONE = 1;
  const CUBE = 2;
  const CYLINDER = 3;
  const LINE = 4;
  const SPHERE = 5;

  public static function getList($key = FALSE)
  {
    $list = array(
      SpellAOE::CONE => 'Cone',
      SpellAOE::CUBE => 'Cube',
      SpellAOE::CYLINDER => 'Cylinder',
      SpellAOE::LINE => 'Line',
      SpellAOE::SPHERE => 'Sphere',
    );

    return getListItem($list, $key);
  }
}

class SpellDamageType
{
  const ACID = 1;
  const BLUDGEONING = 2;
  const COLD = 3;
  const FIRE = 4;
  const FORCE = 5;
  const LIGHTNING = 6;
  const NECROTIC = 7;
  const PIERCING = 8;
  const POISON = 9;
  const PSYCHIC = 10;
  const RADIANT = 11;
  const SLASHING = 12;
  const THUNDER = 13;

  public static function getList($key = FALSE)
  {
    $list = array(
      SpellDamageType::ACID => 'Acid',
      SpellDamageType::BLUDGEONING => 'Bludgeoning', // Bludgeoning
      SpellDamageType::COLD => 'Cold',
      SpellDamageType::FIRE => 'Fire',
      SpellDamageType::FORCE => 'Force',
      SpellDamageType::LIGHTNING => 'Lightning',
      SpellDamageType::NECROTIC => 'Necrotic',
      SpellDamageType::PIERCING => 'Piercing', // Piercing
      SpellDamageType::POISON => 'Poison',
      SpellDamageType::PSYCHIC => 'Psychic',
      SpellDamageType::RADIANT => 'Radiant',
      SpellDamageType::SLASHING => 'Slashing', // Slashing
      SpellDamageType::THUNDER => 'Thunder',
    );

    return getListItem($list, $key);
  }
}