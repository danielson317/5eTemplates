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
  $attr = array(
    'href' => 'item',
  );
  $template->addOperation(htmlWrap('a', 'New Item', $attr));

  if ($page > 1)
  {
    $attr = array(
      'href' => '?page=' . ($page - 1),
    );
    $template->addOperation(htmlWrap('a', 'Prev Page', $attr));
  }

  if (count($items) >= DEFAULT_PAGER_SIZE)
  {
    $attr = array(
      'href' => '?page=' . ($page + 1),
    );
    $template->addOperation(htmlWrap('a', 'Next Page', $attr));
  }

  $attr = array(
    'href' => 'items/print?page=' . $page,
  );
  $template->addOperation(htmlWrap('a', 'Print Cards', $attr));

  // List
  $table = new TableTemplate();
  $table->setAttr('class', array('item-list'));
  $table->setHeader(array('Name', 'Value', 'Type', 'Description'));

  $item_types = getItemTypeList();
  foreach($items as $item)
  {
    $row = array();
    $attr = array(
      'href' => 'item?id=' . $item['id'],
    );
    $row[] = htmlWrap('a', $item['name'], $attr);
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

  // Value.
  $field = new FieldText('value', 'Value (GP)');
  $form->addField($field);

  // Attunement.
  $field = new FieldCheckbox('attunement', 'Requires Attunement');
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

function itemUpsertSubmit()
{
  $item = $_POST;
  $item['attunement'] = isset($_POST['attunement']) ? 1 : 0;
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
      'href' => '?page=' . ($page - 1),
    );
    $template->addOperation(htmlWrap('a', 'Prev Page', $attr));
  }

  if (count($items) >= DEFAULT_PAGER_SIZE)
  {
    $attr = array(
      'href' => '?page=' . ($page + 1),
    );
    $template->addOperation(htmlWrap('a', 'Next Page', $attr));
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

  $form = $table->__toString();

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
  $attr = array(
    'href' => 'item-type',
  );
  $template->addOperation(htmlWrap('a', 'New Item Type', $attr));

  if ($page > 1)
  {
    $attr = array(
      'href' => '?page=' . ($page - 1),
    );
    $template->addOperation(htmlWrap('a', 'Prev Page', $attr));
  }

  if (count($item_types) >= DEFAULT_PAGER_SIZE)
  {
    $attr = array(
      'href' => '?page=' . ($page + 1),
    );
    $template->addOperation(htmlWrap('a', 'Next Page', $attr));
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
