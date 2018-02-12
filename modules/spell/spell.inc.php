<?php

class Spell
{
  // Summary
  protected $name;
  protected $school;
  protected $level;
  protected $speed;
  protected $range;
  protected $components;
  protected $duration;
  protected $description;
  protected $higher_levels;
  protected $subject;

  /***************
   * Operators
   ***************/
  public function __construct()
  {
  }
  public function setSpell($spell)
  {
    $this->name = $spell['name'];
    $this->school = $spell['school'];
    $this->level = $spell['level'];
    $this->speed = $spell['speed'];
    $this->range = $spell['range'];
    $this->components = $spell['components'];
    $this->duration = $spell['duration'];
    $this->description = $spell['description'];
    $this->higher_levels = $spell['higher_levels'];
    $this->subject = $spell['subject'];
  }
  public function __toString()
  {
    ob_start();

    include ROOT_PATH . '/themes/default/templates/spell.tpl.php';

    return ob_get_clean();
  }

  /***************
   * Setters
   ***************/
   public function setName($name)
   {
     $this->name = $name;
   }
   public function setSchool($school)
   {
     $this->school = $school;
   }
   public function setLevel($level)
   {
     $this->level = $level;
   }
   public function setSpeed($speed)
   {
     $this->speed = $speed;
   }
   public function setRange($range)
   {
     $this->range = $range;
   }
   public function setComponents($components)
   {
     $this->components = $components;
   }
   public function setDuration($duration)
   {
     $this->duration = $duration;
   }
   public function setDescription($description)
   {
     $this->description = $description;
   }
   public function setSubject($subject)
   {
     $this->subject = $subject;
   }

   /**********************
    * Getters.
    **********************/

}
