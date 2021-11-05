<?php
//function itemDamageAjax()
//{
//  $response = getAjaxDefaultResponse();
//
//  $operation = getUrlOperation();
//  switch ($operation)
//  {
//    case 'view':
//    {
//      $item_id = getUrlID('item_id');
//      $item_damages = getItemDamageList($item_id);
//      $table = new TableTemplate('item_damage');
//      $table->setHeader(array('Damage', 'Type', 'Versatile'));
//      foreach ($item_damages as $item_damage)
//      {
//        $row = array();
//        $row[] = a($item_damage['die_count'] . 'd' . $item_damage['die_id'], '/ajax/item/damage', array('class' => array('update'), 'query' => array('operation' => 'update', 'item_damage_id' => $item_damage['id'])));
//        $row[] = SpellDamageType::getList($item_damage['damage_type_id']);
//        $row[] = $item_damage['versatile'];
//        $table->addRow($row);
//      }
//
//      $response['data'] = $table->__toString();
//    }
//    case 'create':
//    {
//      $response['data'] = itemDamageForm(0);
//      break;
//    }
//    case 'update':
//    {
//      $item_damage_id = getUrlID('item_damage_id');
//      $response['data'] = itemDamageForm($item_damage_id);
//      break;
//    }
//    default:
//    {
//      $response['status'] = FALSE;
//      break;
//    }
//  }
//
//  jsonResponseDie($response);
//}

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
  $field = new FieldHidden('item_damage_id');
  $field->setValue($item_damage_id);
  $form->addField($field);

  // Item id.
  $field = new FieldHidden('item_id');
  $field->setValue($item_id);
  $form->addField($field);

  // Die count.
  $field = new FieldNumber('die_count', 'Number of Dice');
  $field->setValue(1);
  $form->addField($field);

  // Die id.
  $dice = getDieList();
  $field = new FieldSelect('die_id', 'Die', $dice);
  $field->setValue(6);
  $form->addField($field);

  // Damage type id.
  $damage_types = SpellDamageType::getList();
  $field = new FieldSelect('damage_type_id', 'Damage Type', $damage_types);
  $field->setValue(SpellDamageType::PIERCING);
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

  $item_damages = getItemDamageList($item_id);
  $table = new TableTemplate('item_damage');
  $table->setHeader(array('Damage', 'Type', 'Versatile'));
  foreach ($item_damages as $item_damage)
  {
    $row = array();
    $row[] = a($item_damage['die_count'] . 'd' . $item_damage['die_id'], '/ajax/item/damage', array('class' => array('update'), 'query' => array('operation' => 'update', 'item_damage_id' => $item_damage['id'])));
    $row[] = SpellDamageType::getList($item_damage['damage_type_id']);
    $row[] = $item_damage['versatile'];
    $table->addRow($row);
  }

  $response['data'] = $table->__toString();
  jsonResponseDie($response);
}

function itemDamageUpsertSubmitAjax()
{
  $response = getAjaxDefaultResponse();
  $item_damage = $_POST;

  $operation = getPostOperation();
  if ($operation === 'delete')
  {
    deleteItemDamage($item_damage['item_damage_id']);
  }
  // Update.
  elseif ($item_damage['item_damage_id'])
  {
    updateItemDamage($item_damage);
  }
  // Create.
  else
  {
    createItemDamage($item_damage);
  }

  jsonResponseDie($response);
}
