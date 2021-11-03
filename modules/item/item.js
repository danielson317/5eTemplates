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
}