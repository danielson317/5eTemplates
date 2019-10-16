<div class="spell print-card">
  <div class="name"><?php echo $name; ?></div>
  <div class="school"><?php echo $school; ?> </div>
  <div class="abilities">
    <table class="abilities">
      <thead><tr><th>Speed</th><th>Range</th><th>Duration</th></tr></thead>
      <tbody><tr>
        <td class="speed"><?php echo $speed; ?></td>
        <td class="range"><?php echo $range; ?></td>
        <td class="duration"><?php echo $duration; ?><?php if ($concentration) :  ?><span class="concentration">C</span><?php endif; ?></td>
      </tr></tbody>
    </table>
  </div>
  <div class="components"><?php echo $components; ?></div>
  <div class="description">
    <?php echo $description; ?>
  </div>
  <?php if ($alternate) : ?>
    <div class="alternate-label">At Higher Levels</div>
    <div class="alternate">
      <?php echo $alternate; ?>
    </div>
  <?php endif; ?>
</div>
