<ul class="section__schedule">
  <?php if (isset($data['schedules'])) {
    foreach ($data['schedules'] as $schedule) { ?>
      <li class="schedule__item">
        <p><?= $schedule->getDay() ?></p>
        <p><?= !is_null($schedule->getSchedules()) ?  $schedule->getSchedules() : 'Fermé' ?></p>
      </li>
    <?php  }
    ?>
  <?php  } ?>
</ul>