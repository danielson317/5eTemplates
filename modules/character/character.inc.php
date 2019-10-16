<?php

function characterDisplay($character_id)
{
  $character = getCharacter($character_id);

  // Name.
  $output = '';
  $output .= lineItem('Name', $character['name']);

  // Players.
  $players = getPlayerList();
  $output .= lineItem('Player', $players[$character['player_id']]);

  // Alignment.
  $alignments = getAlignmentList();
  $output .= lineItem('Alignment', $alignments[$character['alignment']]);

  // Race.
  $races = getRaceList();
  $subraces = getSubraceList();
  $race = $races[$character['race_id']];
  if ($character['subrace_id'])
  {
   $race .= ' (' . $subraces[$character['subrace_id']] . ')';
  }
  $output .= lineItem('Race', $race);

  // Class.
  $classes = getClassList();
  $subclasses = getSubclassList();
  $character_classes = getCharacterClassList($character_id);
  foreach($character_classes as $character_class)
  {
    $class = $classes[$character_class['class_id']];
    if ($character_class['subclass_id'])
    {
      $class .= ' (' . $subclasses[$character_class['subclass_id']] . ')';
    }
    $class .= ' ' . $character_class['level'];
    $output .= lineItem('Class', $class);
  }
  $output = htmlWrap('div', $output, array('class' => array('primary', 'group')));

  // Abilities.
  $group = '';
  $abilities = getAbilityList();
  $character_abilities = getCharacterAbilityList($character_id);
  foreach($character_abilities as $character_ability)
  {
    $group .= lineItem($abilities[$character_ability['ability_id']], $character_ability['modifier'] . ' (' . $character_ability['score'] . ')');
  }
  $output .= htmlWrap('div', $group, array('class' => array('abilities', 'group')));

  return htmlWrap('div', $output, array('id' => 'character_summary'));
}

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
  foreach($character_item_type_proficiency_map as $character_item_type_proficiency)
  {
    $proficiency = getItemType($character_item_type_proficiency['item_type_id']);

    // Find the highest parent item type. If the item type is already the highest
    // use itself as the parent.
    $item_type = $proficiency;
    $item_type_id = $proficiency['parent_item_type_id'];
    while ((int)$item_type_id !== 0)
    {
      $item_type = getItemType($item_type_id);
      $item_type_id = $item_type['parent_item_type_id'];
    }
    $item_type_id = $item_type['id'];

    if (!isset($proficiency_types[$item_type_id]))
    {
      $proficiency_types[$item_type_id] = array();
    }
    $proficiency['type'] = 'item_type';
    $proficiency_types[$item_type_id][] = $proficiency;
  }

  // Items.
  $character_item_proficiency_map = getCharacterItemProficiencyList($character_id);
  foreach($character_item_proficiency_map as $character_item_proficiency)
  {
    $proficiency = getItem($character_item_proficiency['item_id']);

    $item_type_id = $proficiency['item_type_id'];
    do
    {
      $item_type = getItemType($item_type_id);
      $item_type_id = $item_type['parent_item_type_id'];
    } while ((int)$item_type['parent_item_type_id'] !== 0);

    if (!isset($proficiency_types[$item_type['id']]))
    {
      $proficiency_types[$item_type['id']] = array();
    }
    $proficiency['type'] = 'item';
    $proficiency['proficiency'] = $character_item_proficiency['proficiency'];
    $proficiency_types[$item_type['id']][] = $proficiency;
  }

  // Render lists.
  $item_types = getItemTypeList();
  foreach($proficiency_types as $proficiency_item_type_id => $proficiency_type)
  {
    $row = array();
    foreach($proficiency_type as $proficiency)
    {
      if ($proficiency['type'] === 'item_type')
      {
        $attr = array(
          'query' => array(
            'item_type_id' => $proficiency['id'],
            'character_id' => $character_id,
          ),
          'class' => array('item-type-proficiency'),
        );
        $row[] = a($proficiency['name'], '/ajax/character/item-type-proficiency', $attr);
      }
      else
      {
        $name = $proficiency['name'];
        if ($proficiency['proficiency'])
        {
          $name .= '(' . $proficiency['proficiency'] . 'x)';
        }

        $attr = array(
          'query' => array(
            'item_id' => $proficiency['id'],
            'character_id' => $character_id,
          ),
          'class' => array('item-proficiency'),
        );
        $row[] = a($name, '/ajax/character/item-proficiency', $attr);
      }
    }
    $proficiency_table->addRow(array($item_types[$proficiency_item_type_id], implode(', ', $row)));
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