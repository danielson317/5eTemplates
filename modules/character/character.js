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
});
