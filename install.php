<?php
include 'libraries/bootstrap.inc.php';

// Confirm this is a fresh install.
GLOBAL $db;
if (!file_exists(DB_PATH))
{
  if (!is_writable(dirname(DB_PATH)))
  {
    die(DB_PATH . ' is not writable. Unable to perform install.');
  }

  // Create the database and it's tables.
  $db = new SQLite(DB_PATH);

  // Interface tables.
  installUser();
  installSession();

  // Dependencies.
  installSource();

  installAbility();
  installSkill(); // Depends on ability.

  installBackground(); // Depends on Source.
  installBackgroundCharacteristics(); // Depends on Source, Background.

  installCharacter();

  installClass(); // Depends on ability, Source
  installSubclass(); // Depends on Class, Source


  installScript(); // Depends on Source.
  installLanguage(); // Depends on Script, Source

  installRace(); // Depends on Source
  installSubrace(); // Depends on Race, Source

  installPlayer();

  installSpell();
  installItem(); // Depends on Source.
//  installMonster(); // Depends on Source.
}

redirect('/');


