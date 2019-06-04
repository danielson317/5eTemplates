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
  installUser();
  installSession();

//  installArmor();
//  installArmorType();
  installAoe();
  installAttribute();
  installBackground();
  installCharacter();
  installClass();
  installDamageType();
  installItem();
  installItemType();
  installLanguage();
//  installMonster();
  installPlayer();
  installRace();
  installRange();
  installSchool();
  installScript();
  installSkill();
  installSource();
  installSpeed();
  installSpell();
  installSubclass();
  installSubrace();
//  installTool();
//  installToolType();
//  installWeapon();
//  installWeaponType();
}

redirect('/');


