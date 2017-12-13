<?php


class CreateDB
{
  private $pdo;

  public function __construct($db_path = 'dnd.db')
  {
    $connect_string = 'sqlite:' . $db_path;
    $this->pdo = new PDO($connect_string);
  }

  public funciton createTables()
  {
    $this->createAlignmentTable();
    $this->createArmorTable();
    $this->createArmorClassesTable();
    $this->createAttributesTable();
    $this->createBackgroundTable();
    $this->createCharacterTable();
    $this->createCharacteristicsTable();
    $this->createDamageTypeTable():
    $this->createFeaturesTable();
    $this->createItemsTable();
    $this->createLanguagesTable();
    $this->createSkillsTable();
    $this->createToolsTable();
    $this->createWeaponsTable();
    $this->createWeaponClassesTable();
  }

  private function createAlignmentTable()
  {
    $sql = 'CREATE TABLE IF NOT EXISTS alignment (
      id INTEGER PRIMARY KEY AUTOINCREMENT,
      name TEXT NOT NULL,
      code TEXT NOT NULL,
      descripiton TEXT
    )';
    $this->pdo->exec($sql);
  }

  private function createArmorTable()
  {
    $sql = 'CREATE TABLE IF NOT EXISTS armor (
      id INTEGER PRIMARY KEY AUTOINCREMENT,
      name TEXT NOT NULL,
      description TEXT,
      modifier INTEGER,
      dex_limit INTEGER, -- 0, 2, or 10?
      noisy INTEGER,
      is_magic INTEGER,
      armor_class_id INTEGER
    )';
    $this->pdo->exec($sql);
  }

  private function createArmorClassesTable()
  {
    $sql = 'CREATE TABLE IF NOT EXISTS armor_classes (
      id INTEGER PRIMARY KEY AUTOINCREMENT,
      name TEXT NOT NULL,
      description TEXT
    )';
    $this->pdo->exec($sql);
  }

  private function createAttributesTable()
  {
    $sql = 'CREATE TABLE IF NOT EXISTS attributes (
      id INTEGER PRIMARY KEY AUTOINCREMENT,
      name TEXT NOT NULL,
      code TEXT NOT NULL,
      descripiton TEXT
    )';
    $this->pdo->exec($sql);
  }

  private function createBackgroundTable()
  {
    $sql = 'CREATE TABLE IF NOT EXISTS backgrounds (
      id INTEGER PRIMARY KEY AUTOINCREMENT,
      name TEXT NOT NULL,
      description TEXT,
      gp INTEGER
    )';
    $this->pdo->exec($sql);
  }

  private function createCharacterTable()
  {
    $sql = 'CREATE TABLE IF NOT EXISTS characters (
      id INTEGER PRIMARY KEY AUTOINCREMENT,
      name TEXT NOT NULL,
      xp INTEGER NOT NULL DEFAULT 0,
      alignment_id INTEGER NOT NULL
    )';
    $this->pdo->exec($sql);
  }

  private function createCharacteristicsTable()
  {
    $sql = 'CREATE TABLE IF NOT EXISTS characteristics (
      id INTEGER PRIMARY KEY AUTOINCREMENT,
      name TEXT NOT NULL,
      description TEXT,
      type TEXT
    )';
    $this->pdo->exec($sql);
  }

  private function createDamageTypeTable()
  {
    $sql = 'CREATE TABLE IF NOT EXISTS damage_type (
      id INTEGER PRIMARY KEY AUTOINCREMENT,
      name TEXT NOT NULL,
      description TEXT
    )';
    $this->pdo->exec($sql);
  }

  private function createFeaturesTable()
  {
    $sql = 'CREATE TABLE IF NOT EXISTS features (
      id INTEGER PRIMARY KEY AUTOINCREMENT,
      name TEXT NOT NULL,
      description TEXT
    )';
    $this->pdo->exec($sql);
  }

  private function createItemsTable()
  {
    $sql = 'CREATE TABLE IF NOT EXISTS items (
      id INTEGER PRIMARY KEY AUTOINCREMENT,
      name TEXT NOT NULL,
      description TEXT
    )';
    $this->pdo->exec($sql);
  }

  private function createLanguagesTable()
  {
    $sql = 'CREATE TABLE IF NOT EXISTS languages (
      id INTEGER PRIMARY KEY AUTOINCREMENT,
      name TEXT NOT NULL,
      description TEXT
    )';
    $this->pdo->exec($sql);
  }

  private function createSkillsTable()
  {
    $sql = 'CREATE TABLE IF NOT EXISTS skills (
      id INTEGER PRIMARY KEY AUTOINCREMENT,
      name TEXT NOT NULL,
      code TEXT NOT NULL,
      description TEXT,
      attribute_id INTEGER
    )';
    $this->pdo->exec($sql);
  }

  private function createToolsTable()
  {
    $sql = 'CREATE TABLE IF NOT EXISTS tools (
      id INTEGER PRIMARY KEY AUTOINCREMENT,
      name TEXT NOT NULL,
      description TEXT
    )';
    $this->pdo->exec($sql);
  }

  private function createWeaponsTable()
  {
    $sql = 'CREATE TABLE IF NOT EXISTS weapons (
      id INTEGER PRIMARY KEY AUTOINCREMENT,
      name TEXT NOT NULL,
      description TEXT,
      damage TEXT,
      damage_type_id INTEGER,
      normal_range INTEGER,
      disadvantage_range INTEGER,
      bonus_attribute_id INTEGER,
      is_magic INTEGER,
      weapon_class_id INTEGER
    )';
    $this->pdo->exec($sql);
  }

  private function createWeaponClassesTable()
  {
    $sql = 'CREATE TABLE IF NOT EXISTS weapon_classes (
      id INTEGER PRIMARY KEY AUTOINCREMENT,
      name TEXT NOT NULL,
      description TEXT
    )';
    $this->pdo->exec($sql);
  }
}
