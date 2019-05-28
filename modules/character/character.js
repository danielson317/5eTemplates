$ = jQuery;
$(document).ready(function()
{
  // View - Refresh the list.
  $('.field.classes').on('refresh', '', function()
  {
    var url = '/ajax/character/class';
    var values =
      {
        operation: 'list',
        character_id: getUrlParameter('id')
      };

    $.get(url, values, function(response)
    {
      if (response['status'])
      {
        $('.classes tbody').replace(response['data']);
      }
    });
  });

  // Create - Add new character class.
  $('.add-class').click(function(e)
  {
    e.preventDefault();
    var url = '/ajax/character/class';
    var values =
    {
      operation: 'create',
      character_id: getUrlParameter('id')
    };

    $.get(url, values, function(response)
    {
      var $modal = modalShow(response);
      characterClassBehaviors($modal, 'create');
    })
  });

  // Update - Edit character class.
  $('.field.classes').on('click', 'a.class',function(e)
  {
    e.preventDefault();
    var url = '/ajax/character/class';
    var values =
      {
        operation: 'update',
        character_id: getUrlParameter('character_id', $(this).attr('href')),
        class_id: getUrlParameter('class_id', $(this).attr('href'))
      };

    $.get(url, values, function(response)
    {
      var $modal = modalShow(response);
      characterClassBehaviors($modal, 'update');
    })
  });

  function characterClassBehaviors($wrapper, $operation)
  {
    $wrapper.find('.field.submit input').click(function(e)
    {
      e.preventDefault();
      var url = '/ajax/character/class';
      var values =
      {
        operation: $operation,
        character_id: $wrapper.find('[name="character_id"]').val(),
        class_id: $wrapper.find('.class_id select').val(),
        subclass_id: $wrapper.find('.subclass_id select').val(),
        level: $wrapper.find('.level input').val()
      };
      $.post(url, values, function()
      {
        $('.field.classes').refresh();
        modalHide();
      });
    });

    $wrapper.find('.field.class_id select').change(function()
    {
      var url = '/ajax/subclass';
      var values = {class_id:$(this).val()};

      $.get(url, values, function(response)
      {
        $('.field.subclass_id select').html(response);
      }, 'html');
    });
  }
});
