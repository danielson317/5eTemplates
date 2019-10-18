<?php

/******************************************************************************
 *
 * List
 *
 ******************************************************************************/

function monsterList()
{
  $page = getUrlID('page', 1);
  $monsters = getMonsterPager($page);

  $template = new ListPageTemplate('monsters');
  $template->addCssFilePath('/themes/default/css/monster.css');

  // Operations.
  $attr = array(
    'href' => 'monster',
  );
  $template->addOperation(htmlWrap('a', 'New monster', $attr));

  if ($page > 1)
  {
    $attr = array(
      'href' => '?page=' . ($page - 1),
    );
    $template->addOperation(htmlWrap('a', 'Prev Page', $attr));
  }

  if (count($monsters) >= PAGER_SIZE_DEFAULT)
  {
    $attr = array(
      'href' => '?page=' . ($page + 1),
    );
    $template->addOperation(htmlWrap('a', 'Next Page', $attr));
  }

//  $attr = array(
//    'href' => 'monsters/print?page=' . $page,
//  );
//  $template->addOperation(htmlWrap('a', 'Print Cards', $attr));

  // List
  $table = new TableTemplate();
  $table->setAttr('class', array('monster-list'));
  $table->setHeader(array('Name', 'Value', 'Type', 'Description'));

//  $monster_types = getmonsterTypeList();
  foreach($monsters as $monster)
  {
    $row = array();
    $attr = array(
      'href' => 'monster?id=' . $monster['id'],
    );
    $row[] = htmlWrap('a', $monster['name'], $attr);
    $row[] = $monster['value'];
//    $row[] = $monster_types[$monster['monster_type_id']];
    $row[] = $monster['description'];
    $table->addRow($row);
  }
  $template->setList($table);
  return $template;
}

/******************************************************************************
 *
 * Upsert
 *
 ******************************************************************************/

function monsterUpsertForm()
{
  $template = new FormPageTemplate();
  $template->addCssFilePath('/themes/default/css/monster.css');

  // Submit.
  if (isset($_SERVER['REQUEST_METHOD']) && ($_SERVER['REQUEST_METHOD'] == 'POST'))
  {
    $template->addMessage(monsterUpsertSubmit());
  }

  $monster_id = getUrlID('id');

  $form = new Form('monster_form');
  $title = 'Add New monster';
  if ($monster_id)
  {
    $monster = getMonster($monster_id);
    $form->setValues($monster);
    $title = 'Edit monster ' . htmlWrap('em', $monster['name']);
  }
  $form->setTitle($title);

  // ID.
  $field = new FieldHidden('id');
  $form->addField($field);

  // Name.
  $field = new FieldText('name', 'Name');
  $form->addField($field);

  // Type.
//  $options = getMonsterTypeList();
//  $field = new FieldSelect('monster_type_id', 'Type', $options);
//  $form->addField($field);

  // Value.
  $field = new FieldText('value', 'Value (GP)');
  $form->addField($field);

  // Magic.
  $field = new FieldCheckbox('magic', 'Magical');
  $form->addField($field);

  // Attunement.
  $field = new FieldCheckbox('attunement', 'Requires Attunement');
  $form->addField($field);

  // Description.
  $field = new FieldTextarea('description', 'Description');
  $form->addField($field);

  // Submit
  $value = 'Create';
  if ($monster_id)
  {
    $value = 'Update';
  }
  $field = new FieldSubmit('submit', $value);
  $form->addField($field);

  $template->setForm($form);

  return $template;
}

function monsterUpsertSubmit()
{
  $monster = $_POST;
  $monster['magic'] = isset($_POST['magic']) ? 1 : 0;
  $monster['attunement'] = isset($_POST['attunement']) ? 1 : 0;
  unset($monster['submit']);

  if ($monster['id'])
  {
    updateMonster($monster);
    return htmlWrap('h3', 'monster ' . htmlWrap('em', $monster['name']) . ' (' . $monster['id'] . ') updated.');
  }
  else
  {
    unset($monster['id']);
    $monster['id'] = createMonster($monster);
    return htmlWrap('h3', 'New monster ' . htmlWrap('em', $monster['name']) . ' (' . $monster['id'] . ') created.');
  }
}

/******************************************************************************
 *
 * Print
 *
 ******************************************************************************/
//
//function monsterPrintForm()
//{
//  if (isset($_SERVER['REQUEST_METHOD']) && ($_SERVER['REQUEST_METHOD'] == 'POST'))
//  {
//    return monsterPrintSubmit();
//  }
//
//  $page = getUrlID('page', 1);
//  $monsters = getMonsterPager($page);
//
//  $template = new ListTemplate();
//  $template->setTitle('Print monster Cards');
//  $template->addJsFilePath('/themes/default/js/print_list.js');
//  $template->setBodyAttr(array('class' => array('print-page')));
//
//  // Operations.
//  if ($page > 1)
//  {
//    $attr = array(
//      'href' => '?page=' . ($page - 1),
//    );
//    $template->addOperation(htmlWrap('a', 'Prev Page', $attr));
//  }
//
//  if (count($monsters) >= DEFAULT_PAGER_SIZE)
//  {
//    $attr = array(
//      'href' => '?page=' . ($page + 1),
//    );
//    $template->addOperation(htmlWrap('a', 'Next Page', $attr));
//  }
//
//  // List.
//  $table = new TableTemplate();
//  $table->setAttr('class', array('monster-print-list', 'print-list'));
//  $table->setHeader(array('Qty', 'Name', 'Value', 'Type', 'Description'));
//
//  $monster_types = getmonsterTypeList();
//  foreach($monsters as $monster)
//  {
//    $row = array();
//
//    // Qty.
//    $qty = '';
//
//    // Count.
//    $attr = array(
//      'type' => CreateQuery::TYPE_INTEGER,
//      'name' => $monster['id'],
//      'class' => array('button'),
//    );
//    $qty .= htmlSolo('input', $attr);
//
//    // Minus.
//    $attr = array(
//      'type' => 'button',
//      'name' => $monster['id'] .'_minus',
//      'value' => '-',
//      'class' => array('minus'),
//      'tabindex' => -1,
//    );
//    $qty .= htmlSolo('input', $attr);
//
//    // Plus.
//    $attr = array(
//      'type' => 'button',
//      'name' => $monster['id'] .'_plus',
//      'value' => '+',
//      'class' => array('plus'),
//      'tabindex' => -1,
//    );
//    $qty .= htmlSolo('input', $attr);
//    $row[] = $qty;
//
//    $row[] = $monster['name'];
//    $row[] = $monster['value'];
//    $row[] = $monster_types[$monster['monster_type_id']];
//    $row[] = $monster['description'];
//    $table->addRow($row);
//  }
//
//  $form = $table->__toString();
//
//  $attr = array(
//    'type' => 'submit',
//    'name' => 'print',
//    'value' => 'Print',
//  );
//  $form .= htmlSolo('input', $attr);
//
//  $attr = array(
//    'id' => 'monster-print-form',
//    'method' => 'post',
//  );
//  $template->setList(htmlWrap('form', $form, $attr));
//
//  return $template;
//}
//
//function monsterPrintSubmit()
//{
//  $template = new HTMLTemplate();
//  $template->addCssFilePath('/themes/default/css/monster.css');
//  $template->setTitle('Print monsters');
//
//  $output = '';
//  $monsters = $_POST;
//  unset($monsters['print']);
//  $monster_types = getmonsterTypeList();
//  foreach($monsters as $monster_id => $qty)
//  {
//    if (!$qty || $qty < 0)
//    {
//      continue;
//    }
//    for ($k = 0; $k < $qty; $k++)
//    {
//      $monster = getmonster($monster_id);
//      $monster['type'] = $monster_types[$monster['monster_type_id']];
//      $monster['magic'] = $monster['magic'] ? 'M' : '';
//      $monster['attunement'] = $monster['attunement'] ? 'A' : '';
//      $output .= printmonsterCard($monster);
//    }
//  }
//  $template->setBody($output);
//  return $template;
//}
//
//function printmonsterCard($monster)
//{
//  extract($monster);
//  ob_start();
//
//  include ROOT_PATH . '/themes/default/templates/monster.tpl.php';
//
//  return ob_get_clean();
//}
