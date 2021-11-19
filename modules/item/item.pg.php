<?php


function itemList()
{
//  installItem();
//  installItemMagic();
//  installItemWeapon();
//  installItemArmor();
//  installItemDamage();
  $page = getUrlID('page', 1);
  $items = getItemPager($page);

  $template = new ListPageTemplate('Items');
  $template->addCssFilePath('/themes/default/css/item.css');

  // Operations.
  $template->addOperation(a('New Item', '/item'));

  if ($page > 1)
  {
    $attr = array(
      'query' => array('page' => ($page - 1)),
    );
    $template->addOperation(a('Prev Page', '/item', $attr));
  }

  if (count($items) >= PAGER_SIZE_DEFAULT)
  {
    $attr = array(
      'query' => array('page' => ($page + 1)),
    );
    $template->addOperation(a('Next Page', '/item', $attr));
  }

  $output = '';
  foreach($items as $item)
  {
    $attr = array(
      'query' => array('id' => $item['id']),
    );
    $group = a($item['name'], '/item', $attr) . htmlSolo('br');
    $group .= lineItem('Category', ItemCategory::getList($item['category_id']));
    $group .= lineItem('Value', $item['value'] ? itemFormatCost($item['value']) : '-');
    $group .= lineItem('Weight', itemWeightFormat($item['weight']));
    if ($item['is_magic'])
    {
      $group .= lineItem('Magical', ItemRarity::getList($item['rarity_id']));
    }
    if (ItemCategory::isWeapon($item['category_id']) && $item['is_weapon'])
    {
      $group .= lineItem('Weapon', itemFormatWeaponProperties($item));
    }
    if (ItemCategory::isArmor($item['category_id']) && $item['is_armor'])
    {
      $group .= lineItem('Armor', itemFormatArmorProperties($item));
    }
    $group .= htmlWrap('strong', 'Description: ') . $item['description'];
    $output .= htmlWrap('div', $group, array('class' => array('item')));
  }

  $output = htmlWrap('div', $output, array('class' => array('items')));
  $template->setList($output);
  return $template;
}

/******************************************************************************
 *
 * Item Upsert
 *
 ******************************************************************************/

function itemUpsertForm()
{
  $template = new FormPageTemplate();
  $template->addCssFilePath('/themes/default/css/item.css');
  $template->addJsFilePath('/modules/item/item.js');

  // Submit.
  if (isset($_SERVER['REQUEST_METHOD']) && ($_SERVER['REQUEST_METHOD'] === 'POST'))
  {
    $template->addMessage(itemUpsertSubmit());
  }

  $item_id = getUrlID('id');

  $form = new Form('item_form');
  $title = 'Add New Item';
  if ($item_id)
  {
    $item = getItem($item_id);
    $form->setValues($item);
    $title = 'Edit item ' . $item['name'];
  }
  $form->setTitle($title);

  // ID.
  $field = new FieldHidden('id');
  $form->addField($field);

  // Submit
  $value = 'Create';
  if ($item_id)
  {
    $value = 'Update';
  }
  $field = new FieldSubmit('submit', $value);
  $form->addField($field);


  /*****************
   * Base items.
   *****************/
  $group = 'base_group';
  $form->addGroup($group);

  // Name.
  $field = new FieldText('name', 'Name');
  $field->setGroup($group);
  $form->addField($field);

  // Value.
  $field = new FieldText('value', 'Cost (cp)');
  $field->setgroup($group);
  $form->addfield($field);

  // weight.
  $field = new fieldtext('weight', 'Weight (lbs)');
  $field->setgroup($group);
  $form->addfield($field);
 
  // Category.
  $options = array(0 => '--none--') + ItemCategory::getHierarchyList();
  $field = new FieldSelect('category_id', 'Category', $options);
  $field->setGroup($group);
  $form->addField($field);

  // Description.
  $field = new FieldTextarea('description', 'Description');
  $field->setGroup($group);
  $form->addField($field);

  // Source.
  $options = array(0 => '--Select One--') + getSourceDetailList();
  $field = new FieldSelect('source_id', 'Source', $options);
  $field->setGroup($group);
  $form->addField($field);

  // Source location (page number).
  $field = new FieldNumber('source_location', 'Page');
  $field->setGroup($group);
  $form->addField($field);

  /****************
   * Weapon Group
   ****************/
  if ($item_id && ItemCategory::isWeapon($item['category_id']))
  {
    $item_weapon = getItemWeapon($item_id);
    if ($item_weapon)
    {
      $form->addValues($item_weapon);
    }
  }

  $group = 'weapon_group';
  $form->addGroup($group);

  // Heading.
  $field = new FieldMarkup('weapon_heading');
  $field->setValue(htmlWrap('h3', 'Weapon Properties'));
  $field->setGroup($group);
  $form->addField($field);

  // Range
  $options = array(0 => '--Select One--') + SpellRange::getList();
  $field = new FieldSelect('range_id', 'Range', $options);
  $field->setGroup($group);
  $form->addField($field);

  // Range
  $options = array(0 => '--Select One--') + SpellRange::getList();
  $field = new FieldSelect('max_range_id', 'Max Range', $options);
  $field->setGroup($group);
  $form->addField($field);

  // Ammunition
  $field = new FieldCheckbox('ammunition', 'Ammunition');
  $field->setGroup($group);
  $form->addField($field);

  // Finesse
  $field = new FieldCheckbox('finesse', 'Finesse');
  $field->setGroup($group);
  $form->addField($field);

  // Heavy
  $field = new FieldCheckbox('heavy', 'Heavy');
  $field->setGroup($group);
  $form->addField($field);

  // Light
  $field = new FieldCheckbox('light', 'Light');
  $field->setGroup($group);
  $form->addField($field);

  // Loading
  $field = new FieldCheckbox('loading', 'Loading');
  $field->setGroup($group);
  $form->addField($field);

  // Reach
  $field = new FieldCheckbox('reach', 'Reach');
  $field->setGroup($group);
  $form->addField($field);

  // Thrown
  $field = new FieldCheckbox('thrown', 'Thrown');
  $field->setGroup($group);
  $form->addField($field);

  // Two-Handed
  $field = new FieldCheckbox('two_handed', 'Two Handed');
  $field->setGroup($group);
  $form->addField($field);

  /****************
   * Armor Group
   ****************/
  $group = 'armor_group';
  $form->addGroup($group);

  if ($item_id && ItemCategory::isArmor($item['category_id']))
  {
    $item_armor = getItemArmor($item_id);
    if ($item_armor)
    {
      $form->addValues($item_armor);
    }
  }

  // Heading.
  $field = new FieldMarkup('armor_heading');
  $field->setValue(htmlWrap('h3', 'Armor'));
  $field->setGroup($group);
  $form->addField($field);

  // Base AC.
  $field = new FieldText('base_ac', 'Base AC');
  $field->setgroup($group);
  $form->addfield($field);

  // Dexterity.
  $field = new FieldText('dex_cap', 'Dex Cap');
  $field->setgroup($group);
  $form->addfield($field);

  // Strength.
  $field = new FieldText('str_score', 'Required Strength Score');
  $field->setgroup($group);
  $form->addfield($field);

  // Disadvantage
  $field = new FieldCheckbox('stealth_disadvantage', 'Disadvantage to Stealth');
  $field->setGroup($group);
  $form->addField($field);

  /****************
   * Magic Group
   ****************/
  $group = 'magic_group';
  $form->addGroup($group);

  // Heading.
  $field = new FieldMarkup('magic_heading');
  $field->setValue(htmlWrap('h3', 'Magic'));
  $field->setGroup($group);
  $form->addField($field);

  // Is magic.
  $field = new FieldCheckbox('is_magic', 'Magical Properties');
  $field->setGroup($group);
  if ($item_id)
  {
    $item_magic = getItemMagic($item_id);
    if ($item_magic)
    {
      $field->setValue(TRUE);
      $form->addValues($item_magic);
    }
  }
  $form->addField($field);

  // Rarity
  $options = ItemRarity::getList();
  $field = new FieldSelect('rarity_id', 'Rarity', $options);
  $field->setGroup($group);
  $form->addField($field);

  // Bonus
  $field = new FieldText('bonus', 'Bonus');
  $field->setGroup($group);
  $form->addField($field);

  $field = new FieldCheckbox('attunement', 'Requires Attunement');
  $field->setGroup($group);
  $form->addField($field);

  $field = new FieldText('attunement_requirements', 'Attunement Requirements');
  $field->setGroup($group);
  $form->addField($field);

  /****************
   * Damage Group
   ****************/
  if ($item_id)
  {
    $group = 'damage_group';
    $form->addGroup($group);

    // Heading.
    $field = new FieldMarkup('damage_heading');
    $field->setValue(htmlWrap('h3', 'Damage'));
    $field->setGroup($group);
    $form->addField($field);

    // Operations.
    $markup = a('Add', '/ajax/item/damage', array('class' => array('create'), 'query' => array('operation' => 'create', 'item_id' => $item['id'])));
    $field = new FieldMarkup('damage_buttons');
    $field->setGroup($group)->setValue($markup);
    $form->addField($field);

    // Table.
    $item_damages = getItemDamageList($item['id']);
    $table = new TableTemplate('item_damage');
    $table->setHeader(array('Damage', 'Type', 'Versatile'));
    foreach ($item_damages as $item_damage)
    {
      $row = array();
      $row[] = a($item_damage['die_count'] . 'd' . $item_damage['die_id'], '/ajax/item/damage', array('class' => array('update'), 'query' => array('operation' => 'update', 'item_damage_id' => $item_damage['id'])));
      $row[] = SpellDamageType::getList($item_damage['damage_type_id']);
      $row[] = formatBool($item_damage['versatile']);
      $table->addRow($row);
    }
    $field = new FieldMarkup('damage_table');
    $field->setGroup($group)->setValue($table->__toString());
    $form->addField($field);
  }
  /***********
   * Handlers
   ***********/
  $template->setForm($form);
  return $template;
}

function itemUpsertSubmit()
{
  $item = $_POST;

  if ($item['id'])
  {
    updateItem($item);
  }
  else
  {
    $item['id'] = createItem($item);
  }

  $item['is_magic'] = iis($item, 'is_magic', 0);
  if ($item['is_magic'])
  {
    $item['attunement'] = iis($item, 'attunement', 0);
    $item_magic = getItemMagic($item['id']);
    if ($item_magic)
    {
      updateitemMagic($item);
    }
    else
    {
      createItemMagic($item);
    }
  }

  if (ItemCategory::isWeapon($item['category_id']))
  {
    $item['ammunition'] = iis($item, 'ammunition', 0);
    $item['finesse'] = iis($item, 'finesse', 0);
    $item['heavy'] = iis($item, 'heavy', 0);
    $item['light'] = iis($item, 'light', 0);
    $item['loading'] = iis($item, 'loading', 0);
    $item['reach'] = iis($item, 'reach', 0);
    $item['thrown'] = iis($item, 'thrown', 0);
    $item['two_handed'] = iis($item, 'two_handed', 0);

    $item_weapon = getItemWeapon($item['id']);
    if ($item_weapon)
    {
      updateItemWeapon($item);
    }
    else
    {
      createItemWeapon($item);
    }
  }

  if (ItemCategory::isArmor($item['category_id']))
  {
    $item['stealth_disadvantage'] = iis($item, 'stealth_disadvantage', 0);

    $item_armor = getItemArmor($item['id']);
    if ($item_armor)
    {
      updateItemArmor($item);
    }
    else
    {
      createItemArmor($item);
    }
  }

  return htmlWrap('h3', 'Item ' . htmlWrap('em', $item['name']) . ' (' . $item['id'] . ') updated.');
}

/******************************************************************************
 *
 * Item Print
 *
 ******************************************************************************/

function itemPrintForm()
{
  if (isset($_SERVER['REQUEST_METHOD']) && ($_SERVER['REQUEST_METHOD'] === 'POST'))
  {
    return itemPrintSubmit();
  }

  $page = getUrlID('page', 1);
  $items = getItemPager($page);

  $template = new ListPageTemplate();
  $template->setTitle('Print Item Cards');
  $template->addJsFilePath('/themes/default/js/print_list.js');
  $template->setBodyAttr(array('class' => array('print-page')));

  // Operations.
  if ($page > 1)
  {
    $attr = array(
      'query' => array('page' => ($page - 1)),
    );
    $template->addOperation(a('Prev Page', '/item/print', $attr));
  }

  if (count($items) >= PAGER_SIZE_DEFAULT)
  {
    $attr = array(
      'query' => array('page' => ($page + 1)),
    );
    $template->addOperation(a('Next Page', '/item/print', $attr));
  }

  // List.
  $table = new TableTemplate();
  $table->setAttr('class', array('item-print-list', 'print-list'));
  $table->setHeader(array('Qty', 'Name', 'Value', 'Type', 'Description'));

  $item_types = getItemTypeList();
  foreach($items as $item)
  {
    $row = array();

    // Qty.
    $qty = '';

    // Count.
    $attr = array(
      'type' => CreateQuery::TYPE_INTEGER,
      'name' => $item['id'],
      'class' => array('button'),
    );
    $qty .= htmlSolo('input', $attr);

    // Minus.
    $attr = array(
      'type' => 'button',
      'name' => $item['id'] .'_minus',
      'value' => '-',
      'class' => array('minus'),
      'tabindex' => -1,
    );
    $qty .= htmlSolo('input', $attr);

    // Plus.
    $attr = array(
      'type' => 'button',
      'name' => $item['id'] .'_plus',
      'value' => '+',
      'class' => array('plus'),
      'tabindex' => -1,
    );
    $qty .= htmlSolo('input', $attr);
    $row[] = $qty;

    $row[] = $item['name'];
    $row[] = $item['value'];
    $row[] = $item_types[$item['item_type_id']];
    $row[] = $item['description'];
    $table->addRow($row);
  }

  $form = htmlWrap('p', 'Print results via chrome. Set margins to minimal, scale to 106, and enable background images for color.');
  $form .= $table->__toString();

  $attr = array(
    'type' => 'submit',
    'name' => 'print',
    'value' => 'Print',
  );
  $form .= htmlSolo('input', $attr);

  $attr = array(
    'id' => 'item-print-form',
    'method' => 'post',
  );
  $template->setList(htmlWrap('form', $form, $attr));

  return $template;
}

function itemPrintSubmit()
{
  $template = new HTMLTemplate();
  $template->addCssFilePath('/themes/default/css/item.css');
  $template->setTitle('Print Items');

  $output = '';
  $items = $_POST;
  unset($items['print']);
  $item_types = getItemTypeList();
  foreach($items as $item_id => $qty)
  {
    if (!$qty || $qty < 0)
    {
      continue;
    }
    for ($k = 0; $k < $qty; $k++)
    {
      $item = getItem($item_id);
      $item['type'] = $item_types[$item['item_type_id']];
      $item['attunement'] = $item['attunement'] ? 'A' : '';
      $output .= printItemCard($item);
    }
  }
  $template->setBody($output);
  return $template;
}

function printItemCard($item)
{
  extract($item);
  ob_start();

  include ROOT_PATH . '/themes/default/templates/item.tpl.php';

  return ob_get_clean();
}

