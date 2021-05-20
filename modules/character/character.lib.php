<?php

function getCharacterProficiencyBonus($level)
{
  return floor((7 + $level) / 4);
}