<?php

/******************************************************************************
 *
 * Items
 *
 ******************************************************************************/

function itemList()
{
  $page = getUrlID('page', 1);
  $items = getItemPager($page);

  $template = new ListPageTemplate('Items');
  $template->addCssFilePath('/themes/default/css/item.css');

  // Operations.
  $template->addOperation(a('New Item', '/item', $attr));

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
    $row[] = a($item['name'], $attr);
    $row[] = $item['value'];
    $row[] = $item_types[$item['item_type_id']];
    $row[] = $item['description'];
    $table->addRow($row);
  }
  $template->setList($table);
  return $template;
}

function itemUpsertForm()
{
  $template = new FormPageTemplate();
  $template->addCssFilePath('/themes/default/css/item.css');

  // Submit.
  if (isset($_SERVER['REQUEST_METHOD']) && ($_SERVER['REQUEST_METHOD'] == 'POST'))
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
    $title = 'Edit item ' . htmlWrap('em', $item['name']);
  }
  $form->setTitle($title);

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

  // Item Type Details.
  $field = new FieldText('item_type_details', 'Type Details');
  $form->addField($field);

  // Value.
  $field = new FieldText('value', 'Value (cp)');
  $form->addField($field);

  // Weight.
  $field = new FieldText('weight', 'Weight (lbs)');
  $form->addField($field);

  // Rarity
  $options = getRarityList();
  $field = new FieldSelect('rarity_id', 'Rarity', $options);
  $form->addField($field);

  // Attunement.
  $field = new FieldCheckbox('attunement', 'Requires Attunement');
  $form->addField($field);

  // Attunement.
  $field = new FieldText('attunement_requirements', 'Attunement Conditions');
  $form->addField($field);

  // Artifact.
  $field = new FieldCheckbox('artifact', 'Artifact');
  $form->addField($field);

  // Description.
  $field = new FieldTextarea('description', 'Description');
  $form->addField($field);

  // Source.
  $options = array(0 => '--Select One--') + getSourceList();
  $field = new FieldSelect('source_id', 'Source', $options);
  $form->addField($field);

  // Source location (page number).
  $field = new FieldNumber('source_location', 'Page');
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
  $item['attunement'] = isset($_POST['attunement']) ? 1 : 0;
  $item['artifact'] = isset($_POST['artifact']) ? 1 : 0;
  unset($item['submit']);

  if ($item['id'])
  {
    updateItem($item);
    return htmlWrap('h3', 'Item ' . htmlWrap('em', $item['name']) . ' (' . $item['id'] . ') updated.');
  }
  else
  {
    unset($item['id']);
    $item['id'] = createItem($item);
    return htmlWrap('h3', 'New item ' . htmlWrap('em', $item['name']) . ' (' . $item['id'] . ') created.');
  }
}

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
 * Item Types
 *
 ******************************************************************************/
function itemTypeList()
{
  $page = getUrlID('page', 1);
  $item_types = getItemTypePager($page);

  $template = new ListPageTemplate('Item Types');
  $template->addCssFilePath('/themes/default/css/item.css');

  // Operations.
  $template->addOperation(a('New Item Type', 'item-type', $attr));

  if ($page > 1)
  {
    $attr = array(
      'query' => array('page' => ($page - 1)),
    );
    $template->addOperation(a('Prev Page', '/item-type', $attr));
  }

  if (count($item_types) >= DEFAULT_PAGER_SIZE)
  {
    $attr = array(
      'query' => array('page' => ($page + 1)),
    );
    $template->addOperation(a('Next Page', '/item-type', $attr));
  }

  // List
  $table = new TableTemplate();
  $table->setAttr('class', array('item-type-list'));
  $table->setHeader(array('Name', 'Description'));

  foreach($item_types as $item_type)
  {
    $row = array();
    $attr = array(
      'href' => 'item-type?id=' . $item_type['id'],
    );
    $row[] = htmlWrap('a', $item_type['name'], $attr);
    $row[] = $item_type['description'];
    $table->addRow($row);
  }
  $template->setList($table);
  return $template;
}

function itemTypeUpsertForm()
{
  $template = new FormPageTemplate();
  $template->addCssFilePath('/themes/default/css/item.css');

  // Submit.
  if (isset($_SERVER['REQUEST_METHOD']) && ($_SERVER['REQUEST_METHOD'] == 'POST'))
  {
    $template->addMessage(itemTypeUpsertSubmit());
  }

  $item_id = getUrlID('id');

  $form = new Form('item_type_form');
  $title = 'Add New Item Type';
  if ($item_id)
  {
    $item = getItemType($item_id);
    $form->setValues($item);
    $title = 'Edit item type ' . htmlWrap('em', $item['name']);
  }
  $form->setTitle($title);

  // ID.
  $field = new FieldHidden('id');
  $form->addField($field);

  // Name.
  $field = new FieldText('name', 'Name');
  $form->addField($field);

  // Description.
  $field = new FieldTextarea('description', 'Description');
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

function itemTypeUpsertSubmit()
{
  $item_type = $_POST;
  unset($item_type['submit']);

  if ($item_type['id'])
  {
    updateItemType($item_type);
    return htmlWrap('h3', 'Item type ' . htmlWrap('em', $item_type['name']) . ' (' . $item_type['id'] . ') updated.');
  }
  else
  {
    unset($item_type['id']);
    $item_type['id'] = createItemType($item_type);
    return htmlWrap('h3', 'New item type' . htmlWrap('em', $item_type['name']) . ' (' . $item_type['id'] . ') created.');
  }
}

/******************************************************************************
 *
 * Rarity.
 *
 ******************************************************************************/
function rarityList()
{
  $page = getUrlID('page', 1);
  $rarities = getRarityPager($page);

  $template = new ListPageTemplate('Rarities');
  $template->addCssFilePath('/themes/default/css/item.css');

  // Operations.
  $template->addOperation(a('New Rarity', '/rarity', $attr));

  if ($page > 1)
  {
    $attr = array(
      'query' => array('page' => ($page - 1)),
    );
    $template->addOperation(a('Prev Page', '/rarity', $attr));
  }

  if (count($rarities) >= DEFAULT_PAGER_SIZE)
  {
    $attr = array(
      'query' => array('page' => ($page + 1)),
    );
    $template->addOperation(a('Prev Page', '/rarity', $attr));
  }

  // List
  $table = new TableTemplate();
  $table->setAttr('class', array('rarity-list'));
  $table->setHeader(array('Name', 'Description'));

  foreach($rarities as $rarity)
  {
    $row = array();
    $attr = array(
      'href' => 'rarity?id=' . $rarity['id'],
    );
    $row[] = htmlWrap('a', $rarity['name'], $attr);
    $row[] = $rarity['description'];
    $table->addRow($row);
  }
  $template->setList($table);
  return $template;
}

function rarityUpsertForm()
{
  $template = new FormPageTemplate();
  $template->addCssFilePath('/themes/default/css/item.css');

  // Submit.
  if (isset($_SERVER['REQUEST_METHOD']) && ($_SERVER['REQUEST_METHOD'] == 'POST'))
  {
    $template->addMessage(rarityUpsertSubmit());
  }

  $item_id = getUrlID('id');

  $form = new Form('rarity_form');
  $title = 'Add Rarity';
  if ($item_id)
  {
    $item = getRarity($item_id);
    $form->setValues($item);
    $title = 'Edit rarity ' . htmlWrap('em', $item['name']);
  }
  $form->setTitle($title);

  // ID.
  $field = new FieldHidden('id');
  $form->addField($field);

  // Name.
  $field = new FieldText('name', 'Name');
  $form->addField($field);

  // Description.
  $field = new FieldTextarea('description', 'Description');
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

function rarityUpsertSubmit()
{
  $rarity = $_POST;
  unset($rarity['submit']);

  if ($rarity['id'])
  {
    updateRarity($rarity);
    return htmlWrap('h3', 'Rarity ' . htmlWrap('em', $rarity['name']) . ' (' . $rarity['id'] . ') updated.');
  }
  else
  {
    unset($rarity['id']);
    $rarity['id'] = createRarity($rarity);
    return htmlWrap('h3', 'New rarity ' . htmlWrap('em', $rarity['name']) . ' (' . $rarity['id'] . ') created.');
  }
}

/******************************************************************************
 *
 * Damage Types
 *
 ******************************************************************************/
function damageTypeList()
{
  $page = getUrlID('page', 1);
  $damage_types = getDamageTypePager($page);

  $template = new ListPageTemplate('Damage Types');
  $template->addCssFilePath('/themes/default/css/item.css');

  // Operations.
  $attr = array(
    'href' => 'damage-type',
  );
  $template->addOperation(htmlWrap('a', 'New Damage Type', $attr));

  if ($page > 1)
  {
    $attr = array(
      'href' => '?page=' . ($page - 1),
    );
    $template->addOperation(htmlWrap('a', 'Prev Page', $attr));
  }

  if (count($damage_types) >= DEFAULT_PAGER_SIZE)
  {
    $attr = array(
      'href' => '?page=' . ($page + 1),
    );
    $template->addOperation(htmlWrap('a', 'Next Page', $attr));
  }

  // List
  $table = new TableTemplate();
  $table->setAttr('class', array('damage-type-list'));
  $table->setHeader(array('Name', 'Code', 'Description'));

  foreach($damage_types as $damage_type)
  {
    $row = array();
    $attr = array(
      'href' => 'damage-type?id=' . $damage_type['id'],
    );
    $row[] = htmlWrap('a', $damage_type['name'], $attr);
    $row[] = $damage_type['code'];
    $row[] = $damage_type['description'];
    $table->addRow($row);
  }
  $template->setList($table);
  return $template;
}

function damageTypeUpsertForm()
{
  $template = new FormPageTemplate();
  $template->addCssFilePath('/themes/default/css/item.css');

  // Submit.
  if (isset($_SERVER['REQUEST_METHOD']) && ($_SERVER['REQUEST_METHOD'] == 'POST'))
  {
    $template->addMessage(damageTypeUpsertSubmit());
  }

  $damage_id = getUrlID('id');

  $form = new Form('damage_type_form');
  $title = 'Add New Damage Type';
  if ($damage_id)
  {
    $damage = getDamageType($damage_id);
    $form->setValues($damage);
    $title = 'Edit damage type ' . htmlWrap('em', $damage['name']);
  }
  $form->setTitle($title);

  // ID.
  $field = new FieldHidden('id');
  $form->addField($field);

  // Name.
  $field = new FieldText('name', 'Name');
  $form->addField($field);

  // Name.
  $field = new FieldText('code', 'Code (2-3 characters)');
  $form->addField($field);

  // Description.
  $field = new FieldTextarea('description', 'Description');
  $form->addField($field);

  // Submit
  $value = 'Create';
  if ($damage_id)
  {
    $value = 'Update';
  }
  $field = new FieldSubmit('submit', $value);
  $form->addField($field);

  $template->setForm($form);

  return $template;
}

function damageTypeUpsertSubmit()
{
  $damage_type = $_POST;
  unset($damage_type['submit']);

  if ($damage_type['id'])
  {
    updateDamageType($damage_type);
    return htmlWrap('h3', 'Damage type ' . htmlWrap('em', $damage_type['name']) . ' (' . $damage_type['id'] . ') updated.');
  }
  else
  {
    unset($damage_type['id']);
    $damage_type['id'] = createDamageType($damage_type);
    return htmlWrap('h3', 'New damage type' . htmlWrap('em', $damage_type['name']) . ' (' . $damage_type['id'] . ') created.');
  }
}

/******************************************************************************
 *
 * Property.
 *
 ******************************************************************************/
function propertyList()
{
  $page = getUrlID('page', 1);
  $properties = getPropertyPager($page);

  $template = new ListPageTemplate('Properties');
  $template->addCssFilePath('/themes/default/css/item.css');

  // Operations.
  $attr = array(
    'href' => 'property',
  );
  $template->addOperation(htmlWrap('a', 'New Property', $attr));

  if ($page > 1)
  {
    $attr = array(
      'href' => '?page=' . ($page - 1),
    );
    $template->addOperation(htmlWrap('a', 'Prev Page', $attr));
  }

  if (count($properties) >= DEFAULT_PAGER_SIZE)
  {
    $attr = array(
      'href' => '?page=' . ($page + 1),
    );
    $template->addOperation(htmlWrap('a', 'Next Page', $attr));
  }

  // List
  $table = new TableTemplate();
  $table->setAttr('class', array('property-list'));
  $table->setHeader(array('Name', 'Description'));

  foreach($properties as $property)
  {
    $row = array();
    $attr = array(
      'href' => 'property?id=' . $property['id'],
    );
    $row[] = htmlWrap('a', $property['name'], $attr);
    $row[] = $property['description'];
    $table->addRow($row);
  }
  $template->setList($table);
  return $template;
}

function propertyUpsertForm()
{
  $template = new FormPageTemplate();
  $template->addCssFilePath('/themes/default/css/item.css');

  // Submit.
  if (isset($_SERVER['REQUEST_METHOD']) && ($_SERVER['REQUEST_METHOD'] == 'POST'))
  {
    $template->addMessage(propertyUpsertSubmit());
  }

  $item_id = getUrlID('id');

  $form = new Form('property_form');
  $title = 'Add Property';
  if ($item_id)
  {
    $item = getProperty($item_id);
    $form->setValues($item);
    $title = 'Edit property ' . htmlWrap('em', $item['name']);
  }
  $form->setTitle($title);

  // ID.
  $field = new FieldHidden('id');
  $form->addField($field);

  // Name.
  $field = new FieldText('name', 'Name');
  $form->addField($field);

  // Description.
  $field = new FieldTextarea('description', 'Description');
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

function propertyUpsertSubmit()
{
  $property = $_POST;
  unset($property['submit']);

  if ($property['id'])
  {
    updateProperty($property);
    return htmlWrap('h3', 'Property ' . htmlWrap('em', $property['name']) . ' (' . $property['id'] . ') updated.');
  }
  else
  {
    unset($property['id']);
    $property['id'] = createProperty($property);
    return htmlWrap('h3', 'New property ' . htmlWrap('em', $property['name']) . ' (' . $property['id'] . ') created.');
  }
}
