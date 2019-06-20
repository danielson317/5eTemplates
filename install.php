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

  installSource();

//  installArmor();
//  installArmorType();
  installAoe();
  installAttribute();
  installBackground(); // Depends on Source.
  installBackgroundCharacteristics(); // Depends on Source, Background.
  installCharacter();
  installClass(); // Depends on Attribute, Source
  installDamageType();
  installDie();
  installItemType();
  installRarity();
  installItem(); // Depends on Source, Rarity, Item Type.
  
  
  installScript(); // Depends on Source.
  installLanguage(); // Depends on Script, Source
//  installMonster();
  installPlayer();
  installRace(); // Depends on Source
  installRange();
  installSchool();
  installSkill(); // Depends on Attribute.
  installSpeed();
  installSpell();
  installSubclass(); // Depends on Class, Source
  installSubrace(); // Depends on Race, Source
//  installTool();
//  installToolType();
//  installWeapon();
//  installWeaponType();
}

redirect('/');


