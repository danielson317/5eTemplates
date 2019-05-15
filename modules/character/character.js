$ = jQuery;
$(document).ready(function()
{
  // Add new character class.
  $('.add-class').click(function(e)
  {
    e.preventDefault();
    var url = '/character/class';
    var values =
    {
      character_id: getUrlParameter('id')
    };

    $.get(url, values, function(response)
    {
      var $modal = modalShow(response);
      characterClassBehaviors($modal);
    })
  });

  function characterClassBehaviors($wrapper)
  {
    $wrapper.find('.field.submit input').click(function(e)
    {
      e.preventDefault();
      var url = '/ajax/character/class';
      var values =
      {
        character_id: $wrapper.find('[name="character_id"]').val(),
        class_id: $wrapper.find('.class_id input').val(),
        subclass_id: $wrapper.find('.class_id input').val(),
        level: $wrapper.find('.level input').val(),
        operation: 'submit'
      };
      $.post(url, values, function()
      {
        modalHide();
      });
    });

    $wrapper.find('.field.class_id select').change(function(e)
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
