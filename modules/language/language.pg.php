<?php
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
  $options = array(0 => '--Select One--') + getSourceDetailList();
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
