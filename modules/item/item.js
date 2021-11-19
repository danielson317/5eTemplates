dnd.categories =
{

}
$(document).ready(function()
{
  let $form = $('#item_form');
  if ($form.length)
  {
    itemFormBehaviors($form);
  }
});

function itemFormBehaviors($form)
{
  let $category = $form.find('.field.category_id select');

  /***********************
   * Magic Group.
   ***********************/

  // Show/Hide is_magic checkbox.
  let $magic_group = $form.find('.magic_group');
  function item_process_is_magic($checkbox)
  {
    if ($checkbox.prop('checked'))
    {
      $magic_group.find('.rarity_id, .bonus, .attunement').show();
    }
    else
    {
      $magic_group.find('.rarity_id, .bonus, .attunement, .attunement_requirements').hide();
    }

    if ($magic_group.find('.field.attunement input').prop('checked'))
    {
      $magic_group.find('.attunement_requirements').show();
    }
  }
  $magic_group.find('.field.is_magic input').click(function(){item_process_is_magic($(this));});
  item_process_is_magic($magic_group.find('.field.is_magic input'));

  // Show/Hide attunement requirements.
  $magic_group.find('.field.attunement input').click(function()
  {
    if ($(this).prop('checked'))
    {
      $magic_group.find('.attunement_requirements').show();
    }
    else
    {
      $magic_group.find('.attunement_requirements').hide();
    }
  });

  /***********************
   * Weapon Group.
   ***********************/
  let $weapon_group = $form.find('.weapon_group');
  function item_process_is_weapon($category)
  {
    // Weapon categories are all 1XX.
    let category_id = parseInt($category.val());
    if (category_id >= 100 && category_id < 200)
    {
      $weapon_group.show();
    }
    else
    {
      $weapon_group.hide();
    }
  }
  $category.change(function(){item_process_is_weapon($(this));});
  item_process_is_weapon($category);

  /***********************
   * Armor Group.
   ***********************/
  let $armor_group = $form.find('.armor_group');
  function item_process_is_armor($category)
  {
    // Armor categories are all 2XX.
    let category_id = parseInt($category.val());
    if (category_id >= 200 && category_id < 300)
    {
      $armor_group.show();
    }
    else
    {
      $armor_group.hide();
    }
  }
  $category.change(function(){item_process_is_armor($(this));});
  item_process_is_armor($category);

  /***********************
   * Damage Group.
   ***********************/
  let $damage_group = $form.find('.damage_group');
  let $damage_table = $damage_group.find('.damage_table .markup-value');

  // Refresh
  $damage_table.refresh(function()
  {
    let url = '/ajax/item/damage';
    let values =
    {
      operation: 'list',
      item_id: getUrlParameter('id'),
    }
    $.get(url, values, function(response)
    {
      if (response['status'])
      {
        $damage_table.find('table').replaceWith(response['data']);
      }
    });
  });

  // Create.
  $damage_group.find('.damage_buttons a.create').click(function(e)
  {
    e.preventDefault();
    let url = $(this).attr('href');
    $.get(url, function(response)
    {
      if (response['status'])
      {
        let $modal = modalShow(response['data']);
        item_damage_form_behaviors($modal, 'create');
      }
    });
  });

  // Update
  $damage_group.find('.damage_table').on('click', 'a.update', function(e)
  {
    e.preventDefault();
    let url = $(this).attr('href');
    $.get(url, function(response)
    {
      if (response['status'])
      {
        let $modal = modalShow(response['data']);
        item_damage_form_behaviors($modal, 'update');
      }
    });
  });
  function item_damage_form_behaviors($modal, operation)
  {
    $modal.find('.field.submit input').click(function(e)
    {
      e.preventDefault();
      let url = '/ajax/item/damage';
      let values =
      {
        operation: operation,
        item_damage_id: $modal.find('input[name="item_damage_id"]').val(),
        item_id: $modal.find('input[name="item_id"]').val(),
        die_count: $modal.find('input[name="die_count"]').val(),
        die_id: $modal.find('select[name="die_id"]').val(),
        damage_type_id: $modal.find('select[name="damage_type_id"]').val(),
        versatile: $modal.find('input[name="versatile"]').prop('checked') ? 1 : 0,
      }
      $.post(url, values, function(response)
      {
        modalHide();
        if (response['status'])
        {
          $damage_table.refresh();
        }
      });
    });

    $modal.find('.field.delete input').click(function(e)
    {
      e.preventDefault();
      let url = '/ajax/item/damage';
      let values =
      {
        operation: 'delete',
        item_damage_id: $modal.find('input[name="item_damage_id"]').val()
      }
      $.post(url, values, function(response)
      {
        modalHide();
        if (response['status'])
        {
          $damage_table.refresh();
        }
      });
    });
  }

}