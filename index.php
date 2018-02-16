<?php
include '/libraries/bootstrap.inc.php';

echo menu();

function printCharacterSheet()
{
  ob_start();
  include ROOT_PATH . '/themes/default/templates/character.tpl.php';
  return ob_get_clean();
}

function printSpellSheet()
{
  $output = '<head>';
  $output .= '<link href="/themes/default/css/spell.css" rel="stylesheet" type="text/css">';
  $output .= '</head>';
  $output .= '<body>';
  $spell = new Spell();

  $divine_smite = array(
    'name' => 'Divine Smite',
    'level' => 2,
    'school' => 'Skill',
    'speed' => 'action',
    'range' => '5',
    'components' => 'N/A',
    'duration' => 'instant',
    'description' => 'When you hit a creature with a melee weapon attack, you can expend one spell slot to deal radiant damage to the target, in addition to the weapon\'s damage. The extra damage is 2d8 for a 1st-level spell slot. The damage increases by 1d8 for undead and fiend.',
    'higher_levels' => 'Add 1d8 radiant damage for each spell level above the first to a maximum of 5d8.',
    'subject' => 'Paladin',
  );
  $spell->setSpell($divine_smite);
  $output .= $spell;
  $output .= '</body>';
  die($output);
}

function printItemCards()
{
  GLOBAL $db;

  $output = '<head>';
  $output .= '<link href="/themes/default/css/page.css" rel="stylesheet" type="text/css">';
  $output .= '<link href="/themes/default/css/item.css" rel="stylesheet" type="text/css">';
  $output .= '</head>';
  $output .= '<body>';

//  $sql = 'SELECT
//            i.name AS name,
//            it.name AS item_type,
//            i.value AS value,
//            i.magic AS magic,
//            i.attunment AS attunment,
//            i.description AS description,
//            i.print AS count
//          FROM items i
//          LEFT JOIN item_types it on i.item_type_id = it.id';
//  $items = $db->select($sql);
  $items = getItemPager();
  foreach($items as $item)
  {
    $item_object = new Item($item);
    for ($k = 0; $k < $item['count']; $k++)
    {
      $output .= $item_object;
    }
  }

  $output .= '</body>';
  die($output);
}
