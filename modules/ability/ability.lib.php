<?php

function getAbilityModifier($score)
{
  return floor(($score - 10) / 2);
}

function getSkillModifier($ability_score, $level, $proficiency_multiplier)
{
  return floor(getCharacterProficiencyBonus($level) * $proficiency_multiplier) + getAbilityModifier($ability_score);
}