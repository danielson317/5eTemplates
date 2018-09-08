$ = jQuery;
$(document).ready(function()
{
  $('.field.class_id select').change(function(e)
  {
    var url = '/ajax/subclass';
    var values = {class_id:$(this).val()};

    $.get(url, values, function(response)
    {
      $('.field.subclass_id select').html(response);
    }, 'html');
  });

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
      var $modal = showModal(response);
      $modal.find('.name-submit input').click(function()
      {
        values =
        {

        };
        $.post(url, values, function()
        {

        });
      });
    })
  });
});
