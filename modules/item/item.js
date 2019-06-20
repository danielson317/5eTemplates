
$(document).ready(function() 
{

  /**********************
   * Item Damage.
   **********************/
  var $item_damage_group = $('.field.damage');
  // View - Refresh the list.
  $item_damage_group.refresh(function()
  {
    var url = '/ajax/item/damage';
    var values =
    {
      operation: 'list',
      item_id: getUrlParameter('id')
    };

    $.get(url, values, function(response)
    {
      if (response['status'])
      {
        $('.damage tbody').html(response['data']);
      }
    });
  });

  // Create - Add new damage type.
  $item_damage_group.find('.add-damage').click(function(e)
  {
    e.preventDefault();
    var url = '/ajax/item/damage';
    var values =
      {
        operation: 'create',
        item_id: getUrlParameter('id')
      };

    $.get(url, values, function (response) 
    {
      var $modal = modalShow(response['data']);
      itemDamageBehaviors($modal, 'create');
    })
  });

  // Update - Edit character class.
  $item_damage_group.on('click', 'a.item-damage', function(e)
  {
    e.preventDefault();
    var url = '/ajax/item/damage';
    var values =
      {
        operation: 'update',
        item_damage_id: getUrlParameter('item_damage_id', $(this).attr('href')),
      };

    $.get(url, values, function (response)
    {
      var $modal = modalShow(response['data']);
      itemDamageBehaviors($modal, 'update');
    })
  });

  function itemDamageBehaviors($wrapper, $operation)
  {
    // Submit.
    $wrapper.find('.field.submit input').click(function (e) {
      e.preventDefault();
      var url = '/ajax/item/damage';
      var values =
        {
          operation: $operation,
          id: $wrapper.find('[name="id"]').val(),
          item_id: $wrapper.find('[name="item_id"]').val(),
          damage_type_id: $wrapper.find('[name="damage_type_id"]').val(),
          die_count: $wrapper.find('[name="die_count"]').val(),
          die_id: $wrapper.find('[name="die_id"]').val()
        };
      $.post(url, values, function () {
        $item_damage_group.refresh();
        modalHide();
      });
    });

    // Delete.
    $wrapper.find('.field.delete').click(function (e) {
      e.preventDefault();
      var url = '/ajax/item/damage';
      var values =
      {
        delete: 1,
        id: $wrapper.find('[name="id"]').val(),
      };
      $.post(url, values, function ()
      {
        $item_damage_group.refresh();
        modalHide();
      });
    });
  }
});