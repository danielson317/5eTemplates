<?php

function characterWizardRaceForm()
{
  $template = new FormPageTemplate();
  $template->addCssFilePath('/themes/default/css/character.css');
  $template->addJsFilePath('/modules/character/character.js');

  $form = new Form('character_wizard_race');
  $form->setTitle('Choose your race.');
  
  $form_item = new FieldText('name', 'Name');
  $form->addField($form_item);
  
  $races = array('empty' => '--Select One--') + getRaceList();
  $form_item = new FieldSelect('race_id', 'Race', $races);
  $form->addField($form_item);
  
  $subraces = array();
  $form_item = new FieldSelect('subrace_id', 'Subrace', $subraces);
  $form_item->setAttr('class', array('disabled'));
  $form->addField($form_item);
  
  $template->setForm($form);
  return $template;
}
