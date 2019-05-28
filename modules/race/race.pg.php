<?php

/******************************************************************************
 *
 *  Race List
 *
 ******************************************************************************/
function raceList()
{
  $page = getUrlID('page', 1);
  $races = getRacePager($page);

  $template = new ListPageTemplate('Race');

  // Operations.
  $template->addOperation(a('New Race', '/race', $attr));

  if ($page > 1)
  {
    $attr = array(
      'query' => array('page' => ($page - 1)),
    );
    $template->addOperation(a('Prev Page', '/race', $attr));
  }

  if (count($races) >= DEFAULT_PAGER_SIZE)
  {
    $attr = array(
      'query' => array('page' => ($page + 1)),
    );
    $template->addOperation(a('next Page', '/race', $attr));
  }

  // List
  $table = new TableTemplate();
  $table->setAttr('race', array('race-list'));
  $table->setHeader(array('Name', 'Description'));

  foreach ($races as $race)
  {
    $row = array();
    $attr = array(
      'query' => array('id' => $race['id']),
    );
    $row[] = a($race['name'], '/race', $attr);
    $row[] = $race['description'];
    $table->addRow($row);
  }
  $template->setList($table);
  return $template;
}

/******************************************************************************
 *
 * Race Upsert
 *
 ******************************************************************************/
function raceUpsertForm()
{
  $template = new FormPageTemplate();

  // Submit.
  if (isset($_SERVER['REQUEST_METHOD']) && ($_SERVER['REQUEST_METHOD'] == 'POST'))
  {
    $template->addMessage(raceUpsertSubmit());
  }

  $race_id = getUrlID('id');

  $form = new Form('race_form');
  $title = 'Add new race';
  if ($race_id)
  {
    $race = getRace($race_id);
    $form->setValues($race);
    $title = 'Edit race ' . htmlWrap('em', $race['name']);
  }
  $form->setTitle($title);

  // ID.
  $field = new FieldHidden('id');
  $form->addField($field);

  // Name
  $field = new FieldText('name', 'Name');
  $form->addField($field);

  // Speed.
  $field = new FieldNumber('speed', 'Base Movement Speed');
  $form->addField($field);

  // Description.
  $field = new FieldTextarea('description', 'Description');
  $form->addField($field);

  // Subraces
  if ($race_id)
  {
    $subraces = getSubracePager($race_id);

    $table = new TableTemplate('subraces');
    $table->setHeader(array('Subrace', 'Description'));
    foreach ($subraces as $subrace)
    {
      $row = array();
      $attr = array(
        'query' => array('id' => $subrace['id']),
      );
      $row[] = a($subrace['name'], '/subrace', $attr);
      $row[] = $subrace['description'];
      $table->addRow($row);
    }

    $attr = array(
      'query' => array('race_id' => $race['id']),
    );
    $link = a('Add New Subrace', '/subrace', $attr);

    $field = new FieldMarkup('subraces', '', $table . $link);
    $form->addField($field);
  }

  // Source.
  $options = array(0 => '--Select One--') + getSourceList();
  $field = new FieldSelect('source_id', 'Source', $options);
  $form->addField($field);

  // Submit
  $value = 'Add';
  if ($race_id)
  {
    $value = 'Update';
  }
  $field = new FieldSubmit('submit', $value);
  $form->addField($field);

  // Delete.
  $field = new FieldSubmit('delete', 'Delete');
  $form->addField($field);

  // Template.
  $template->setForm($form);
  return $template;
}

function raceUpsertSubmit()
{
  if (isset($_POST['delete']))
  {
    deleteRace($_POST['id']);
    redirect('/races');
  }

  $race = $_POST;
  unset($race['submit']);

  // Update.
  if ($_POST['id'])
  {
    updateRace($race);
    return htmlWrap('h3', 'Race ' . htmlWrap('em', $race['name']) . ' (' . $race['id'] . ') updated.');
  }
  // Create.
  else
  {
    unset($race['id']);
    $race['id'] = createRace($race);
    return htmlWrap('h3', 'Race ' . htmlWrap('em', $race['name']) . ' (' . $race['id'] . ') created.');
  }
}

/******************************************************************************
 *
 * Subrace List
 *
 ******************************************************************************/
function subraceAjax()
{
  $race_id = getUrlID('race_id');

  $list = array(0 => '--Select One--') + getSubraceList($race_id);

  die(optionList($list));
}

/******************************************************************************
 *
 * Subrace Upsert
 *
 ******************************************************************************/
function subraceUpsertForm()
{
  $template = new FormPageTemplate();

  // Submit.
  if (isset($_SERVER['REQUEST_METHOD']) && ($_SERVER['REQUEST_METHOD'] == 'POST'))
  {
    $template->addMessage(subraceUpsertSubmit());
  }

  $subrace_id = getUrlID('id');

  $form = new Form('subrace_form');
  if ($subrace_id)
  {
    $subrace = getSubrace($subrace_id);
    $race_id = $subrace['race_id'];
    $race = getRace($subrace['race_id']);
    $form->setValues($subrace);
    $title = 'Edit subrace ' . htmlWrap('em', $subrace['name']) . ' of race ' . htmlWrap('em', $race['name']);
  }
  else
  {
    $race_id = getUrlID('race_id');
    $race = getRace($race_id);
    $title = 'Add new subrace to race ' . htmlWrap('em', $race['name']);
  }
  $form->setTitle($title);

  // ID.
  $field = new FieldHidden('id');
  $form->addField($field);

  // Race ID.
  $field = new FieldHidden('race_id');
  $field->setValue($race['id']);
  $form->addField($field);

  // Sub races.
  $subraces = getSubracePager($race_id);
  $table = new TableTemplate('subraces');
  $table->setHeader(array('Subrace', 'Description'));
  foreach ($subraces as $subrace)
  {
    $row = array();
    $attr = array(
      'query' => array('id' => $subrace['id']),
    );
    $row[] = a($subrace['name'], '/subrace', $attr);
    $row[] = $subrace['description'];
    $table->addRow($row);
  }

  $link = '';
  if ($subrace_id)
  {
    $attr = array(
      'query' => array('race_id' => $race['id']),
    );
    $link = a('Add New Subrace', '/subrace', $attr);
  }

  $field = new FieldMarkup('subraces', '', $table . $link);
  $form->addField($field);

  // Name
  $field = new FieldText('name', 'Name');
  $form->addField($field);

  // Description.
  $field = new FieldTextarea('description', 'Description');
  $form->addField($field);

  // Source.
  $options = array(0 => '--Select One--') + getSourceList();
  $field = new FieldSelect('source_id', 'Source', $options);
  $form->addField($field);

  // Back link.
  $attr = array(
    'query' => array('id' => $race['id']),
  );
  $link = a('Back to race ' . $race['name'], '/race', $attr);

  $field = new FieldMarkup('links', '', $link);
  $form->addField($field);

  // Submit
  $value = 'Add';
  if ($subrace_id)
  {
    $value = 'Update';
  }
  $field = new FieldSubmit('submit', $value);
  $form->addField($field);

  // Delete.
  $field = new FieldSubmit('delete', 'Delete');
  $form->addField($field);

  // Template.
  $template->setForm($form);
  return $template;
}

function subraceUpsertSubmit()
{
  $subrace = $_POST;
  unset($subrace['submit']);

  // Delete.
  if (isset($_POST['delete']))
  {
    deleteSubrace($subrace['id']);
    $attr = array('query' => array('id' => $subrace['race_id']));
    redirect('/race', 303, $attr);
  }

  // Update.
  if ($_POST['id'])
  {
    unset($subrace['race_id']);
    updateSubrace($subrace);
    return htmlWrap('h3', 'Sublass ' . htmlWrap('em', $subrace['name']) . ' (' . $subrace['id'] . ') updated.');
  }
  // Create.
  else
  {
    unset($subrace['id']);
    $subrace['id'] = createSubrace($subrace);
    return htmlWrap('h3', 'Subrace ' . htmlWrap('em', $subrace['name']) . ' (' . $subrace['id'] . ') created.');
  }
}

/******************************************************************************
 *
 * Script List
 *
 ******************************************************************************/
function scriptList()
{
  $page = getUrlID('page', 1);
  $scripts = getScriptPager($page);

  $template = new ListPageTemplate('Scripts');

  // Operations.
  $template->addOperation(a('New Script', '/script', $attr));

  if ($page > 1)
  {
    $attr = array(
      'query' => array('page' => ($page - 1)),
    );
    $template->addOperation(a('Prev Page', '/script', $attr));
  }

  if (count($scripts) >= DEFAULT_PAGER_SIZE)
  {
    $attr = array(
      'query' => array('page' => ($page + 1)),
    );
    $template->addOperation(a('Next Page', '/script', $attr));
  }

  // List
  $table = new TableTemplate();
  $table->setAttr('class', array('script-list'));
  $table->setHeader(array('Name', 'Description'));

  foreach ($scripts as $script)
  {
    $row = array();
    $attr = array(
      'query' => array('id' => $script['id'])
    );
    $row[] = a($script['name'], '/script', $attr);
    $row[] = $script['description'];
    $table->addRow($row);
  }
  $template->setList($table);
  return $template;
}

/******************************************************************************
 *
 * Script Upsert
 *
 ******************************************************************************/
function scriptUpsertForm()
{
  $template = new FormPageTemplate();

  // Submit.
  if (isset($_SERVER['REQUEST_METHOD']) && ($_SERVER['REQUEST_METHOD'] == 'POST'))
  {
    $template->addMessage(scriptUpsertSubmit());
  }

  $script_id = getUrlID('id');

  $form = new Form('script_form');
  $title = 'Add New Script';
  if ($script_id)
  {
    $script = getScript($script_id);
    $form->setValues($script);
    $title = 'Edit script ' . htmlWrap('em', $script['name']);
  }
  $form->setTitle($title);

  // ID.
  $field = new FieldHidden('id');
  $form->addField($field);

  // Name
  $field = new FieldText('name', 'Name');
  $form->addField($field);

  // Source
  $options = array(0 => '--Select One--') + getSourceList();
  $field = new FieldSelect('source_id', 'Source', $options);
  $form->addField($field);

  // Description.
  $field = new FieldTextarea('description', 'Description');
  $form->addField($field);

  // Submit
  $value = 'Add';
  if ($script_id)
  {
    $value = 'Update';
  }
  $field = new FieldSubmit('submit', $value);
  $form->addField($field);

  // Delete.
  if ($script_id)
  {
    $field = new FieldSubmit('delete', 'Delete');
    $form->addField($field);
  }

  // Template.
  $template->setForm($form);
  return $template;
}

function scriptUpsertSubmit()
{
  $script = $_POST;
  unset($script['submit']);

  if (isset($_POST['delete']))
  {
    deleteScript($script['id']);
    redirect('/scripts');
  }

  // Update.
  if ($script['id'])
  {
    updateScript($script);
    return htmlWrap('h3', 'Script ' . htmlWrap('em', $script['name']) . ' (' . $script['id'] . ') updated.');
  }
  // Create.
  else
  {
    unset($script['id']);
    $script['id'] = createScript($script);
    return htmlWrap('h3', 'Script ' . htmlWrap('em', $script['name']) . ' (' . $script['id'] . ') created.');
  }
}

/******************************************************************************
 *
 * Language List
 *
 ******************************************************************************/
function languageList()
{
  $page = getUrlID('page', 1);
  $languages = getLanguagePager($page);

  $template = new ListPageTemplate('Languages');

  // Operations.
  $template->addOperation(a('New Language', '/language', $attr));

  if ($page > 1)
  {
    $attr = array(
      'query' => array('page' => ($page - 1)),
    );
    $template->addOperation(a('Prev Page', '/language', $attr));
  }

  if (count($languages) >= DEFAULT_PAGER_SIZE)
  {
    $attr = array(
      'query' => array('page' => ($page + 1)),
    );
    $template->addOperation(a('Next Page', '/language', $attr));
  }

  // List
  $table = new TableTemplate();
  $table->setAttr('class', array('language-list'));
  $table->setHeader(array('Name', 'Script', 'Description'));

  $scripts = getScriptList();
  foreach ($languages as $language)
  {
    $row = array();
    $attr = array(
      'query' => array('id' => $language['id']),
    );
    $row[] = a($language['name'], '/language', $attr);
    $row[] = $scripts[$language['script_id']];
    $row[] = $language['description'];
    $table->addRow($row);
  }
  $template->setList($table);
  return $template;
}

/******************************************************************************
 *
 * Language Upsert
 *
 ******************************************************************************/
function languageUpsertForm()
{
  $template = new FormPageTemplate();

  // Submit.
  if (isset($_SERVER['REQUEST_METHOD']) && ($_SERVER['REQUEST_METHOD'] == 'POST'))
  {
    $template->addMessage(languageUpsertSubmit());
  }

  $language_id = getUrlID('id');

  $form = new Form('language_form');
  $title = 'Add New Language';
  if ($language_id)
  {
    $language = getLanguage($language_id);
    $form->setValues($language);
    $title = 'Edit language ' . htmlWrap('em', $language['name']);
  }
  $form->setTitle($title);

  // ID.
  $field = new FieldHidden('id');
  $form->addField($field);

  // Name
  $field = new FieldText('name', 'Name');
  $form->addField($field);

  // Script
  $options = array(0 => '--Select One--') + getScriptList();
  $field = new FieldSelect('script_id', 'Script', $options);
  $form->addField($field);

  // Source
  $options = array(0 => '--Select One--') + getSourceList();
  $field = new FieldSelect('source_id', 'Source', $options);
  $form->addField($field);

  // Description.
  $field = new FieldTextarea('description', 'Description');
  $form->addField($field);

  // Submit
  $value = 'Add';
  if ($language_id)
  {
    $value = 'Update';
  }
  $field = new FieldSubmit('submit', $value);
  $form->addField($field);

  // Delete.
  if ($language_id)
  {
    $field = new FieldSubmit('delete', 'Delete');
    $form->addField($field);
  }

  // Template.
  $template->setForm($form);
  return $template;
}

function languageUpsertSubmit()
{
  $language = $_POST;
  unset($language['submit']);

  if (isset($_POST['delete']))
  {
    deleteLanguage($language['id']);
    redirect('/languages');
  }

  // Update.
  if ($language['id'])
  {
    updateLanguage($language);
    return htmlWrap('h3', 'Language ' . htmlWrap('em', $language['name']) . ' (' . $language['id'] . ') updated.');
  }
  // Create.
  else
  {
    unset($language['id']);
    $language['id'] = createLanguage($language);
    return htmlWrap('h3', 'Language ' . htmlWrap('em', $language['name']) . ' (' . $language['id'] . ') created.');
  }
}
