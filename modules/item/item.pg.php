<?php


function itemList()
{
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

  if (count($items) >= DEFAULT_PAGER_SIZE)
  {
    $attr = array(
      'query' => array('page' => ($page + 1)),
    );
    $template->addOperation(a('Next Page', '/item', $attr));
  }

  $attr = array(
    'query' => array('page' => $page),
  );
  $template->addOperation(a('Print Cards', '/items/print', $attr));

  // List
  $table = new TableTemplate();
  $table->setAttr('class', array('item-list'));
  $table->setHeader(array('Name', 'Value', 'Type', 'Description'));

  $item_types = getItemTypeList();
  foreach($items as $item)
  {
    $row = array();
    $attr = array(
      'query' => array('id' => $item['id']),
    );
    $row[] = a($item['name'], '/item', $attr);
    $row[] = $item['value'];
    $row[] = $item_types[$item['item_type_id']];
    $row[] = $item['description'];
    $table->addRow($row);
  }
  $template->setList($table);
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

  /*****************
   * Base items.
   *****************/
  // ID.
  $field = new FieldHidden('id');
  $form->addField($field);

  // Name.
  $field = new FieldText('name', 'Name');
  $form->addField($field);

  // Type.
  $options = getItemTypeList();
  $field = new FieldSelect('item_type_id', 'Type', $options);
  $form->addField($field);

  // Value.
  $field = new FieldText('value', 'Value (cp)');
  $form->addField($field);

  // Weight.
  $field = new FieldText('weight', 'Weight (lbs)');
  $form->addField($field);

  // Description.
  $field = new FieldTextarea('description', 'Description');
  $form->addField($field);

  // Source.
  $options = array(0 => '--Select One--') + getSourceDetailList();
  $field = new FieldSelect('source_id', 'Source', $options);
  $form->addField($field);

  // Source location (page number).
  $field = new FieldNumber('source_location', 'Page');
  $form->addField($field);

  /*****************
   * Magic.
   *****************/
  // Magical items.
  $field = new FieldCheckbox('magic', 'Magical');
  $form->addField($field);

  // Rarity.
  $options = getRarityList();
  $field = new FieldSelect('rarity_id', 'Rarity', $options);
  $form->addField($field);

  // Bonus.
  $field = new FieldNumber('bonus', 'Weapon/Armor Bonus');
  $form->addField($field);

  // Attunement.
  $field = new FieldCheckbox('attunement', 'Requires Attunement');
  $form->addField($field);

  // Attunement Requirements.
  $field = new FieldText('attunement_requirements', 'Attunement Conditions');
  $form->addField($field);

  /*****************
   * Weapons.
   *****************/
  // Range.
  $ranges = getRangeList();
  $field = new FieldSelect('range_id', 'Range', $ranges);
  $form->addField($field);

  // Max Range.
  $field = new FieldSelect('max_range_id', 'Max Range', $ranges);
  $form->addField($field);

  // Light.
  $field = new FieldCheckbox('light', 'Light');
  $form->addField($field);

  // Finesse.
  $field = new FieldCheckbox('finesse', 'Finesse');
  $form->addField($field);

  // Thrown.
  $field = new FieldCheckbox('thrown', 'Thrown');
  $form->addField($field);

  // Ammunition.
  $field = new FieldCheckbox('ammunition', 'Ammunition');
  $form->addField($field);

  // Loading.
  $field = new FieldCheckbox('loading', 'Loading');
  $form->addField($field);

  // Heavy.
  $field = new FieldCheckbox('heavy', 'Heavy');
  $form->addField($field);

  // Reach.
  $field = new FieldCheckbox('reach', 'Reach');
  $form->addField($field);

  // Special.
  $field = new FieldCheckbox('special', 'Special');
  $form->addField($field);

  // Two Handed.
  $field = new FieldCheckbox('two_handed', 'Two Handed');
  $form->addField($field);

  // Damage
  $damage_type = getDamageTypeList();
  $item_damages = getItemDamageList($item_id);
  $die = getDieList();

  $table = new TableTemplate();
  foreach($item_damages as $item_damage)
  {
    $row = array();
    $attr = array(
      'query' => array(
        'item_damage_id' => $item_damage['id'],
      ),
      'class' => array('item-damage'),
    );
    $row[] = a($damage_type[$item_damage['damage_type_id']], '/ajax/item/damage', $attr);
    $row[] = $item_damage['die_count'] . $die[$item_damage['die_id']];
    $row[] = $item_damage['versatile'] ? 'Two-Handed' : '';
    $table->addRow($row);
  }
  $attr = array(
    'query' => array('item_id' => $item_id),
    'class' => array('add-damage'),
  );
  $link = a('Add Damage Type', '/ajax/item/damage', $attr);

  $field = new FieldMarkup('damage', 'Damage', $table . $link);
  $form->addField($field);

  /*****************
   * Armor.
   *****************/
  // Base AC.
  $field = new FieldNumber('base_ac', 'Base AC');
  $form->addField($field);

  // Dexterity Cap.
  $field = new FieldNumber('dex_cap', 'Dexterity Cap');
  $form->addField($field);

  // Strength Requirement.
  $field = new FieldNumber('strength_requirement', 'Strength Requirement');
  $form->addField($field);

  // Stealth Disadvantage.
  $field = new FieldNumber('stealth_disadvantage', 'Stealth Disadvantage');
  $form->addField($field);

  // Submit
  $value = 'Create';
  if ($item_id)
  {
    $value = 'Update';
  }
  $field = new FieldSubmit('submit', $value);
  $form->addField($field);

  $template->setForm($form);

  return $template;
}

function itemUpsertSubmit()
{
  $item = $_POST;

  if ($item['id'])
  {
    updateItem($item);
    return htmlWrap('h3', 'Item ' . htmlWrap('em', $item['name']) . ' (' . $item['id'] . ') updated.');
  }
  else
  {
    $item['id'] = createItem($item);
    return htmlWrap('h3', 'New item ' . htmlWrap('em', $item['name']) . ' (' . $item['id'] . ') created.');
  }
}

/******************************************************************************
 *
 * Item Print
 *
 ******************************************************************************/

function itemPrintForm()
{
  if (isset($_SERVER['REQUEST_METHOD']) && ($_SERVER['REQUEST_METHOD'] == 'POST'))
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

  if (count($items) >= DEFAULT_PAGER_SIZE)
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
      'type' => 'integer',
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

/******************************************************************************
 *
 * Item Damage Upsert
 *
 ******************************************************************************/
function itemDamageUpsertFormAjax()
{
  $response = getAjaxDefaultResponse();

  $operation = getUrlOperation();
  if ($operation === 'list')
  {
    itemDamageListAjax();
  }
  if (isset($_SERVER['REQUEST_METHOD']) && ($_SERVER['REQUEST_METHOD'] == 'POST'))
  {
    itemDamageUpsertSubmitAjax();
  }

  $form = new Form('item_damage_form');

  $item_id = getUrlID('item_id');
  $item_damage_id = getUrlID('item_damage_id');
  if ($item_damage_id)
  {
    $item_damage = getItemDamage($item_damage_id);
    $form->setValues($item_damage);
    $operation = 'update';
    $title = 'Edit Damage Type';
  }
  elseif ($item_id)
  {
    $operation = 'create';
    $title = 'Add New Damage Type';
  }
  else
  {
    $response['status'] = FALSE;
    $response['data'] = 'Missing parameter item_id.';
    jsonResponseDie($response);
    die();
  }
  $form->setTitle($title);

  $field = new FieldHidden('operation', $operation);
  $form->addField($field);

  // Item damage id.
  $field = new FieldHidden('id');
  $form->addField($field);

  // Item id.
  $field = new FieldHidden('item_id');
  $field->setValue($item_id);
  $form->addField($field);

  // Die count.
  $field = new FieldNumber('die_count', 'Number of Dice');
  $form->addField($field);

  // Die id.
  $dice = getDieList();
  $field = new FieldSelect('die_id', 'Die', $dice);
  $form->addField($field);

  // Damage type id.
  $damage_types = getDamageTypeList();
  $field = new FieldSelect('damage_type_id', 'Damage Type', $damage_types);
  $form->addField($field);

  // Versatile.
  $field = new FieldCheckbox('versatile', 'Versatile');
  $form->addField($field);

  // Delete
  if ($operation === 'update')
  {
    $field = new FieldSubmit('delete', 'Delete');
    $form->addField($field);
  }

  // Submit
  $field = new FieldSubmit('submit', 'Submit');
  $form->addField($field);

  $response['data'] = $form->__toString();

  jsonResponseDie($response);
}

function itemDamageListAjax()
{
  $response = getAjaxDefaultResponse();
  $item_id = getUrlID('item_id');

  // Damage
  $damage_types = getDamageTypeList();
  $item_damages = getItemDamageList($item_id);
  $dice = getDieList();

  $output = '';
  foreach($item_damages as $item_damage)
  {
    $row = array();
    $attr = array(
      'query' => array(
        'item_damage_id' => $item_damage['id'],
      ),
      'class' => array('item-damage'),
    );
    $row[] = a($damage_types[$item_damage['damage_type_id']], '/ajax/item/damage', $attr);
    $row[] = $item_damage['die_count'] . $dice[$item_damage['die_id']];
    $row[] = $item_damage['versatile'] ? 'Two-Handed' : '';
    $output .= TableTemplate::tableRow($row);
  }

  $response['data'] = $output;
  jsonResponseDie($response);
}

function itemDamageUpsertSubmitAjax()
{
  $response = getAjaxDefaultResponse();
  $item_damage = $_POST;

  if (isset($item_damage['delete']))
  {
    deleteItemDamage($item_damage['id']);
  }
  // Create.
  elseif ($item_damage['operation'] === 'create')
  {
    createItemDamage($item_damage);
  }
  // Update.
  elseif ($item_damage['operation'] === 'update')
  {
    updateItemDamage($item_damage);
  }

  jsonResponseDie($response);
}
