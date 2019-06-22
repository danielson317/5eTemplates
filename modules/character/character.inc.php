<?php

function printCharacterSheet()
{
  ob_start();
  include ROOT_PATH . '/themes/default/templates/character.tpl.php';
  return ob_get_clean();
}

function getAlignmentList()
{
  return array(
    'lg' => 'Lawful Good',
    'ng' => 'Neutral Good',
    'cg' => 'Chaotic Good',
    'ln' => 'Lawful Neutral',
    'cn' => 'Chaotic Neutral',
    'le' => 'Lawful Evil',
    'ne' => 'Neutral Evil',
    'ce' => 'Chaotic Evil',
    'l' => 'Lawful',
    'n' => 'Neutral',
    'c' => 'Chaotic',
    'g' => 'Good',
    'e' => 'Evil',
  );
}

function getCharacterProficiencyTable($character_id)
{
  $proficiency_table = new TableTemplate('proficiencies');

  // Languages
  $languages = getLanguageList();
  $character_language_maps = getCharacterLanguageList($character_id);
  $list = array();
  foreach($character_language_maps as $character_language_map)
  {
    $attr = array(
      'query' => array(
        'character_id' => $character_id,
        'language_id' => $character_language_map['language_id'],
      ),
      'class' => array('language'),
    );
    $list[] = a($languages[$character_language_map['language_id']], '/ajax/character/language', $attr);
  }
  $proficiency_table->addRow(array('Languages', implode(', ', $list)));

  $proficiency_types = array();
  // Items types.
  $character_item_type_proficiency_map = getCharacterItemTypeProficiencyList($character_id);
//  foreach($character_item_type_proficiency_map as $character_item_proficiency)
//  {
//    $item = getItem($character_item_proficiency['item_id']);
//
//    do
//    {
//      $item_type_id = $item['item_type_id'];
//      $item_type = getItemType($item_type_id);
//    } while ($item_type['parent_item_type_id'] !== 0);
//
//    if (!isset($proficiency_types[$item_type_id]))
//    {
//      $proficiency_types[$item_type_id] = array();
//    }
//    $proficiency_types[$item_type_id][] = $item;
//  }

  // Items.
  $character_item_proficiency_map = getCharacterItemProficiencyList($character_id);
  foreach($character_item_proficiency_map as $character_item_proficiency)
  {
    $item = getItem($character_item_proficiency['item_id']);

    do
    {
      $item_type_id = $item['item_type_id'];
      $item_type = getItemType($item_type_id);
    } while ($item_type['parent_item_type_id'] !== 0);

    if (!isset($proficiency_types[$item_type_id]))
    {
      $proficiency_types[$item_type_id] = array();
    }
    $proficiency_types[$item_type_id][] = $item;
  }

  // Render lists.
  $item_types = getItemTypeList();
  foreach($proficiency_types as $proficiency_type)
  {
    $row = array();
    foreach($proficiency_type as $item)
    {
      $attr = array(
        'query' => array(
          'item_id' => $item['id'],
          'character_id' => $character_id,
        ),
      );
      $row[] = a($item['name'], '/ajax/character/item-proficiency', $attr);
    }
    $proficiency_table->addRow(array($item_types[$item['item_type_id']], implode(', ', $row)));
  }

  // Create links.
  $links = array();
  $attr = array(
    'query' => array('character_id' => $character_id),
    'class' => array('add-language'),
  );
  $links[] = a('Add Language', '/ajax/character/language', $attr);

  $attr = array(
    'query' => array('character_id' => $character_id),
    'class' => array('add-item-proficiency'),
  );
  $links[] = a('Add Item', '/ajax/character/item-proficiency', $attr);

  $attr = array(
    'query' => array('character_id' => $character_id),
    'class' => array('add-item-type-proficiency'),
  );
  $links[] = a('Add Item Type', '/ajax/character/item-type-proficiency', $attr);

  $proficiency_table->addRow(array(join(', ', $links)), array('colspan' => 2));

  return $proficiency_table;
}