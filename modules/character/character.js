$(document).ready(function()
{
  let $character_wizard_race_form = $('#character_wizard_race_form');
  if ($character_wizard_race_form.length)
  {
    $character_wizard_race_form.find('.field.race_id select').change(function()
    {
      $.get(u('/ajax/subrace'), {race_id: $(this).val()}, function(response)
      {
        $('.field.subrace_id select').html(response);
      });
    });
  }

  let $character_form = $('#character_form');
  if ($character_form.length)
  {
    /**********************
     * Classes.
     **********************/
      // View - Refresh the list.
    let $classes = $('.field.classes');
    $classes.refresh(function ()
    {
      let url = '/ajax/character/class';
      let values =
        {
          operation: 'list',
          character_id: getUrlParameter('id')
        };

      $.get(url, values, function (response)
      {
        if (response['status'])
        {
          $('.classes tbody').html(response['data']);
        }
      });
    });

    // Create - Add new character class.
    $('.add-class').click(function (e)
    {
      e.preventDefault();
      let url = '/ajax/character/class';
      let values =
        {
          operation: 'create',
          character_id: getUrlParameter('id')
        };

      $.get(url, values, function (response)
      {
        let $modal = modalShow(response['data']);
        characterClassBehaviors($modal, 'create');
      })
    });

    // Update - Edit character class.
    $classes.on('click', 'a.class', function (e)
    {
      e.preventDefault();
      let url = '/ajax/character/class';
      let values =
        {
          operation: 'update',
          character_id: getUrlParameter('character_id', $(this).attr('href')),
          class_id: getUrlParameter('class_id', $(this).attr('href'))
        };

      $.get(url, values, function (response)
      {
        let $modal = modalShow(response['data']);
        characterClassBehaviors($modal, 'update');
      })
    });

    function characterClassBehaviors($wrapper, $operation)
    {
      // Update subclass list.
      $wrapper.find('.field.class_id select').change(function ()
      {
        let url = '/ajax/subclass';
        let values = {class_id: $(this).val()};

        $.get(url, values, function (response)
        {
          $('.field.subclass_id select').html(response);
        }, 'html');
      });

      // Submit.
      $wrapper.find('.field.submit input').click(function (e)
      {
        e.preventDefault();
        let url = '/ajax/character/class';
        let values =
          {
            operation: $operation,
            character_id: $wrapper.find('[name="character_id"]').val(),
            class_id: $wrapper.find('[name="class_id"]').val(),
            subclass_id: $wrapper.find('.subclass_id select').val(),
            level: $wrapper.find('.level input').val()
          };
        $.post(url, values, function ()
        {
          $classes.refresh();
          modalHide();
        });
      });

      // Delete.
      $wrapper.find('.field.delete').click(function (e)
      {
        e.preventDefault();
        let url = '/ajax/character/class';
        let values =
          {
            delete: 1,
            character_id: $wrapper.find('[name="character_id"]').val(),
            class_id: $wrapper.find('[name="class_id"]').val()
          };
        $.post(url, values, function ()
        {
          $classes.refresh();
          modalHide();
        });
      });
    }

    /**********************
     * abilities.
     **********************/
    var $abilities = $('.field.ability');
    // View - Refresh the list.
    $abilities.refresh(function ()
    {
      let url = '/ajax/character/ability';
      let values =
        {
          operation: 'list',
          character_id: getUrlParameter('id')
        };

      $.get(url, values, function (response)
      {
        if (response['status'])
        {
          $('.ability tbody').html(response['data']);
        }
      });
    });

    // Create - Add new character ability.
    $abilities.click(function (e)
    {
      e.preventDefault();
      let url = '/ajax/character/ability';
      let values =
        {
          operation: 'create',
          character_id: getUrlParameter('id')
        };

      $.get(url, values, function (response)
      {
        let $modal = modalShow(response['data']);
        characterabilityBehaviors($modal, 'create');
      })
    });

    // Update - Edit character ability.
    $abilities.on('click', 'a.ability', function (e)
    {
      e.preventDefault();
      let url = '/ajax/character/ability';
      let values =
        {
          operation: 'update',
          character_id: getUrlParameter('character_id', $(this).attr('href')),
          ability_id: getUrlParameter('ability_id', $(this).attr('href'))
        };

      $.get(url, values, function (response)
      {
        var $modal = modalShow(response['data']);
        characterabilityBehaviors($modal, 'update');
      })
    });

    function characterabilityBehaviors($wrapper, $operation)
    {
      // Submit.
      $wrapper.find('.field.submit input').click(function (e)
      {
        e.preventDefault();
        let url = '/ajax/character/ability';
        let values =
          {
            operation: $operation,
            character_id: $wrapper.find('[name="character_id"]').val(),
            ability_id: $wrapper.find('[name="ability_id"]').val(),
            score: $wrapper.find('[name="score"]').val(),
            modifier: $wrapper.find('[name="modifier"]').val(),
            proficiency: $wrapper.find('[name="proficiency"]').val(),
            saving_throw: $wrapper.find('[name="saving_throw"]').val()
          };
        $.post(url, values, function ()
        {
          $abilities.refresh();
          modalHide();
        });
      });

      // Delete.
      $wrapper.find('.field.delete').click(function (e)
      {
        e.preventDefault();
        let url = '/ajax/character/ability';
        let values =
          {
            delete: 1,
            character_id: $wrapper.find('[name="character_id"]').val(),
            ability_id: $wrapper.find('[name="ability_id"]').val()
          };
        $.post(url, values, function ()
        {
          $abilities.refresh();
          modalHide();
        });
      });
    }

    /**********************
     * Skills.
     **********************/
    let $skills = $('.field.skills');
    // View - Refresh the list.
    $skills.refresh(function ()
    {
      let url = '/ajax/character/skill';
      let values =
        {
          operation: 'list',
          character_id: getUrlParameter('id')
        };

      $.get(url, values, function (response)
      {
        if (response['status'])
        {
          $('.skills tbody').html(response['data']);
        }
      });
    });

    // Create - Add new character skill.
    $('.add-skill').click(function (e)
    {
      e.preventDefault();
      let url = '/ajax/character/skill';
      let values =
        {
          operation: 'create',
          character_id: getUrlParameter('id')
        };

      $.get(url, values, function (response)
      {
        let $modal = modalShow(response['data']);
        characterSkillBehaviors($modal, 'create');
      })
    });

    // Update - Edit character skill.
    $skills.on('click', 'a.skill', function (e)
    {
      e.preventDefault();
      let url = '/ajax/character/skill';
      let values =
        {
          operation: 'update',
          character_id: getUrlParameter('character_id', $(this).attr('href')),
          skill_id: getUrlParameter('skill_id', $(this).attr('href'))
        };

      $.get(url, values, function (response)
      {
        let $modal = modalShow(response['data']);
        characterSkillBehaviors($modal, 'update');
      })
    });

    function characterSkillBehaviors($wrapper, $operation)
    {
      // Submit.
      $wrapper.find('.field.submit input').click(function (e)
      {
        e.preventDefault();
        let url = '/ajax/character/skill';
        let values =
          {
            operation: $operation,
            character_id: $wrapper.find('[name="character_id"]').val(),
            skill_id: $wrapper.find('[name="skill_id"]').val(),
            proficiency: $wrapper.find('[name="proficiency"]').val(),
            modifier: $wrapper.find('[name="modifier"]').val(),
          };
        $.post(url, values, function ()
        {
          $skills.refresh();
          modalHide();
        });
      });

      // Delete.
      $wrapper.find('.field.delete').click(function (e)
      {
        e.preventDefault();
        let url = '/ajax/character/skill';
        let values =
          {
            delete: 1,
            character_id: $wrapper.find('[name="character_id"]').val(),
            skill_id: $wrapper.find('[name="skill_id"]').val()
          };
        $.post(url, values, function ()
        {
          $skills.refresh();
          modalHide();
        });
      });
    }

    /**********************
     * Proficiencies.
     **********************/
    let $proficiencies = $('.proficiencies');

    // View - Refresh the list.
    $proficiencies.refresh(function ()
    {
      let url = '/ajax/character/proficiencies';
      let values =
        {
          operation: 'list',
          character_id: getUrlParameter('id')
        };

      $.get(url, values, function (response)
      {
        if (response['status'])
        {
          $('.proficiencies table').html(response['data']);
        }
      });
    });

    // Language create.
    $proficiencies.on('click', '.add-language', function (e)
    {
      e.preventDefault();
      let url = '/ajax/character/language';
      let values =
        {
          operation: 'create',
          character_id: getUrlParameter('id')
        };

      $.get(url, values, function (response)
      {
        let $modal = modalShow(response['data']);
        characterLanguageBehaviors($modal, 'create');
      })
    });

    // Item proficiency create.
    $proficiencies.on('click', '.add-item-proficiency', function (e)
    {
      e.preventDefault();
      let url = '/ajax/character/item-proficiency';
      let values =
        {
          operation: 'create',
          character_id: getUrlParameter('id')
        };

      $.get(url, values, function (response)
      {
        let $modal = modalShow(response['data']);
        characterItemProficiencyBehaviors($modal, 'create');
      })
    });

    // Item type proficiency create.
    $proficiencies.on('click', '.add-item-type-proficiency', function (e)
    {
      e.preventDefault();
      let url = '/ajax/character/item-type-proficiency';
      let values =
        {
          operation: 'create',
          character_id: getUrlParameter('id')
        };

      $.get(url, values, function (response)
      {
        let $modal = modalShow(response['data']);
        characterItemTypeProficiencyBehaviors($modal, 'create');
      })
    });

    // Delete character language.
    $proficiencies.on('click', 'a.language', function (e)
    {
      e.preventDefault();
      let url = '/ajax/character/language';
      let values =
        {
          operation: 'update',
          character_id: getUrlParameter('character_id', $(this).attr('href')),
          language_id: getUrlParameter('language_id', $(this).attr('href'))
        };

      $.get(url, values, function (response)
      {
        let $modal = modalShow(response['data']);
        characterLanguageBehaviors($modal, 'update');
      })
    });

    // Update/Delete character item proficiency.
    $proficiencies.on('click', 'a.item-proficiency', function (e)
    {
      e.preventDefault();
      let url = '/ajax/character/item-proficiency';
      let values =
        {
          operation: 'update',
          character_id: getUrlParameter('character_id', $(this).attr('href')),
          item_id: getUrlParameter('item_id', $(this).attr('href'))
        };

      $.get(url, values, function (response)
      {
        let $modal = modalShow(response['data']);
        characterItemProficiencyBehaviors($modal, 'update');
      })
    });

    // Delete character item type proficiency.
    $proficiencies.on('click', 'a.item-type-proficiency', function (e)
    {
      e.preventDefault();
      let url = '/ajax/character/item-type-proficiency';
      let values =
        {
          operation: 'update',
          character_id: getUrlParameter('character_id', $(this).attr('href')),
          item_type_id: getUrlParameter('item_type_id', $(this).attr('href'))
        };

      $.get(url, values, function (response)
      {
        let $modal = modalShow(response['data']);
        characterItemTypeProficiencyBehaviors($modal, 'update');
      })
    });

    function characterLanguageBehaviors($wrapper, $operation)
    {
      // Submit.
      $wrapper.find('.field.submit input').click(function (e)
      {
        e.preventDefault();
        let url = '/ajax/character/language';
        let values =
          {
            operation: $operation,
            character_id: $wrapper.find('[name="character_id"]').val(),
            language_id: $wrapper.find('[name="language_id"]').val(),
          };
        $.post(url, values, function ()
        {
          $proficiencies.refresh();
          modalHide();
        });
      });

      // Delete.
      $wrapper.find('.field.delete').click(function (e)
      {
        e.preventDefault();
        let url = '/ajax/character/language';
        let values =
          {
            delete: 1,
            character_id: $wrapper.find('[name="character_id"]').val(),
            language_id: $wrapper.find('[name="language_id"]').val()
          };
        $.post(url, values, function ()
        {
          $proficiencies.refresh();
          modalHide();
        });
      });
    }

    function characterItemProficiencyBehaviors($wrapper, $operation)
    {
      // Submit.
      $wrapper.find('.field.submit input').click(function (e)
      {
        e.preventDefault();
        let url = '/ajax/character/item-proficiency';
        let values =
          {
            operation: $operation,
            character_id: $wrapper.find('[name="character_id"]').val(),
            item_id: $wrapper.find('[name="item_id"]').val(),
            proficiency: $wrapper.find('[name="proficiency"]').val()
          };
        $.post(url, values, function ()
        {
          $proficiencies.refresh();
          modalHide();
        });
      });

      // Delete.
      $wrapper.find('.field.delete').click(function (e)
      {
        e.preventDefault();
        let url = '/ajax/character/item-proficiency';
        let values =
          {
            delete: 1,
            character_id: $wrapper.find('[name="character_id"]').val(),
            item_id: $wrapper.find('[name="item_id"]').val()
          };
        $.post(url, values, function ()
        {
          $proficiencies.refresh();
          modalHide();
        });
      });
    }

    function characterItemTypeProficiencyBehaviors($wrapper, $operation)
    {
      // Submit.
      $wrapper.find('.field.submit input').click(function (e)
      {
        e.preventDefault();
        let url = '/ajax/character/item-type-proficiency';
        let values =
          {
            operation: $operation,
            character_id: $wrapper.find('[name="character_id"]').val(),
            item_type_id: $wrapper.find('[name="item_type_id"]').val(),
          };
        $.post(url, values, function ()
        {
          $proficiencies.refresh();
          modalHide();
        });
      });

      // Delete.
      $wrapper.find('.field.delete').click(function (e)
      {
        e.preventDefault();
        let url = '/ajax/character/item-type-proficiency';
        let values =
          {
            delete: 1,
            character_id: $wrapper.find('[name="character_id"]').val(),
            item_type_id: $wrapper.find('[name="item_type_id"]').val()
          };
        $.post(url, values, function ()
        {
          $proficiencies.refresh();
          modalHide();
        });
      });
    }
  }
});
