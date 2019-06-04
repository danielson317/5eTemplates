<?php
/******************************************************************************
 *
 * Item Types
 *
 ******************************************************************************/

/**
 * @return ListPageTemplate
 */
function itemTypeList()
{
  $page = getUrlID('page', 1);
  $item_types = getItemTypePager($page);

  $template = new ListPageTemplate('Item Types');
  $template->addCssFilePath('/themes/default/css/item.css');

  // Operations.
  $template->addOperation(a('New Item Type', 'item-type'));

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
