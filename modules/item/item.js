
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
  /***********************
   * Magic Group.
   ***********************/
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
  $magic_group.find('.field.is_magic input').click(function()
  {
    item_process_is_magic($(this));
  });
  item_process_is_magic($magic_group.find('.field.is_magic input'));

  function item_process_attunement()
  {
    if ($(this).prop('checked'))
    {
      $magic_group.find('.attunement_requirements').show();
    }
    else
    {
      $magic_group.find('.attunement_requirements').hide();
    }
  }
  $magic_group.find('.field.attunement input').click(item_process_attunement);
}