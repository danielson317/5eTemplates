<?php

class Character
{
  // Summary
  protected $class = array(); // 'class_id' => level (5);
  protected $background;
  protected $player;
  protected $race;
  protected $alignment;
  protected $xp;

  public function __construct()
  {
  }

/************************
 * Summary
 ************************/
  // Name
  protected $name;
  public function getName()
  {
    return $this->name;
  }
  public function setName($name)
  {
    $this->name = $name;
  }
}

class CharachterClass
{
  protected $subclass;
  protected $level;

  public function __construct($class, $subclass, $level)
  {
    $this->class = $class;
    $this->subclass = $subclass;
    $this->level = $level;
  }

  // Class
  protected $class;
  function getClass()
  {
    return $this->class;
  }
  function setClass($class)
  {
    $this->class = $class;
  }
}
