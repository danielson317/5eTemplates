$ = jQuery;
$(document).ready(function()
{
  /**********************
   * Classes.
   **********************/
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
        $('.classes tbody').html(response['data']);
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
      var $modal = modalShow(response['data']);
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
      var $modal = modalShow(response['data']);
      characterClassBehaviors($modal, 'update');
    })
  });

  function characterClassBehaviors($wrapper, $operation)
  {
    // Update subclass list.
    $wrapper.find('.field.class_id select').change(function()
    {
      var url = '/ajax/subclass';
      var values = {class_id:$(this).val()};

      $.get(url, values, function(response)
      {
        $('.field.subclass_id select').html(response);
      }, 'html');
    });

    // Submit.
    $wrapper.find('.field.submit input').click(function(e)
    {
      e.preventDefault();
      var url = '/ajax/character/class';
      var values =
      {
        operation: $operation,
        character_id: $wrapper.find('[name="character_id"]').val(),
        class_id: $wrapper.find('[name="class_id"]').val(),
        subclass_id: $wrapper.find('.subclass_id select').val(),
        level: $wrapper.find('.level input').val()
      };
      $.post(url, values, function()
      {
        $('.field.classes').refresh();
        modalHide();
      });
    });

    // Delete.
    $wrapper.find('.field.delete').click(function(e)
    {
      e.preventDefault();
      var url = '/ajax/character/class';
      var values =
      {
        delete: 1,
        character_id: $wrapper.find('[name="character_id"]').val(),
        class_id: $wrapper.find('[name="class_id"]').val()
      };
      $.post(url, values, function()
      {
        $('.field.classes').refresh();
        modalHide();
      });
    });
  }

  /**********************
   * Attributes.
   **********************/
  // View - Refresh the list.
  $('.field.attributes').on('refresh', '', function()
  {
    var url = '/ajax/character/attribute';
    var values =
      {
        operation: 'list',
        character_id: getUrlParameter('id')
      };

    $.get(url, values, function(response)
    {
      if (response['status'])
      {
        $('.attributes tbody').html(response['data']);
      }
    });
  });

  // Create - Add new character attribute.
  $('.add-attribute').click(function(e)
  {
    e.preventDefault();
    var url = '/ajax/character/attribute';
    var values =
      {
        operation: 'create',
        character_id: getUrlParameter('id')
      };

    $.get(url, values, function(response)
    {
      var $modal = modalShow(response['data']);
      characterAttributeBehaviors($modal, 'create');
    })
  });

  // Update - Edit character attribute.
  $('.field.attributes').on('click', 'a.attribute',function(e)
  {
    e.preventDefault();
    var url = '/ajax/character/attribute';
    var values =
      {
        operation: 'update',
        character_id: getUrlParameter('character_id', $(this).attr('href')),
        attribute_id: getUrlParameter('attribute_id', $(this).attr('href'))
      };

    $.get(url, values, function(response)
    {
      var $modal = modalShow(response['data']);
      characterAttributeBehaviors($modal, 'update');
    })
  });

  function characterAttributeBehaviors($wrapper, $operation)
  {
    // Submit.
    $wrapper.find('.field.submit input').click(function(e)
    {
      e.preventDefault();
      var url = '/ajax/character/attribute';
      var values =
      {
        operation: $operation,
        character_id: $wrapper.find('[name="character_id"]').val(),
        attribute_id: $wrapper.find('[name="attribute_id"]').val(),
        score: $wrapper.find('[name="score"]').val(),
        modifier: $wrapper.find('[name="modifier"]').val(),
        proficiency: $wrapper.find('[name="proficiency"]').val(),
        saving_throw: $wrapper.find('[name="saving_throw"]').val()
      };
      $.post(url, values, function()
      {
        $('.field.attributes').refresh();
        modalHide();
      });
    });

    // Delete.
    $wrapper.find('.field.delete').click(function(e)
    {
      e.preventDefault();
      var url = '/ajax/character/attribute';
      var values =
        {
          delete: 1,
          character_id: $wrapper.find('[name="character_id"]').val(),
          attribute_id: $wrapper.find('[name="attribute_id"]').val()
        };
      $.post(url, values, function()
      {
        $('.field.attributes').refresh();
        modalHide();
      });
    });
  }

  /**********************
   * Skills.
   **********************/
  // View - Refresh the list.
  $('.field.skills').on('refresh', '', function()
  {
    var url = '/ajax/character/skill';
    var values =
      {
        operation: 'list',
        character_id: getUrlParameter('id')
      };

    $.get(url, values, function(response)
    {
      if (response['status'])
      {
        $('.skills tbody').html(response['data']);
      }
    });
  });

  // Create - Add new character skill.
  $('.add-skill').click(function(e)
  {
    e.preventDefault();
    var url = '/ajax/character/skill';
    var values =
      {
        operation: 'create',
        character_id: getUrlParameter('id')
      };

    $.get(url, values, function(response)
    {
      var $modal = modalShow(response['data']);
      characterSkillBehaviors($modal, 'create');
    })
  });

  // Update - Edit character skill.
  $('.field.skills').on('click', 'a.skill', function (e) {
    e.preventDefault();
    var url = '/ajax/character/skill';
    var values =
      {
        operation: 'update',
        character_id: getUrlParameter('character_id', $(this).attr('href')),
        skill_id: getUrlParameter('skill_id', $(this).attr('href'))
      };

    $.get(url, values, function (response) {
      var $modal = modalShow(response['data']);
      characterSkillBehaviors($modal, 'update');
    })
  });
  refreshSkillUpdate();

  function characterSkillBehaviors($wrapper, $operation)
  {
    // Submit.
    $wrapper.find('.field.submit input').click(function(e)
    {
      e.preventDefault();
      var url = '/ajax/character/skill';
      var values =
        {
          operation: $operation,
          character_id: $wrapper.find('[name="character_id"]').val(),
          skill_id: $wrapper.find('[name="skill_id"]').val(),
          proficiency: $wrapper.find('[name="proficiency"]').val(),
          modifier: $wrapper.find('[name="modifier"]').val(),
        };
      $.post(url, values, function()
      {
        $('.field.skills').refresh();
        modalHide();
      });
    });

    // Delete.
    $wrapper.find('.field.delete').click(function(e)
    {
      e.preventDefault();
      var url = '/ajax/character/skill';
      var values =
        {
          delete: 1,
          character_id: $wrapper.find('[name="character_id"]').val(),
          skill_id: $wrapper.find('[name="skill_id"]').val()
        };
      $.post(url, values, function()
      {
        $('.field.skills').refresh();
        modalHide();
      });
    });
  }
});
