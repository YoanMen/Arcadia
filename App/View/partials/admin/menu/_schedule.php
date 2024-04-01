<?php

use App\Model\Schedule; ?>

<article name="service" class="dashboard__element dashboard__element--full-width ">
  <div class="dashboard__element__top">
    <h1 class="hidden--mobile">Horaires</h1>
  </div>
  <div class="dashboard__element__table">
    <table aria-describedby="table for service">
      <thead>
        <tr>
          <th class="hidden--mobile"></th>
          <th>ouverture</th>
          <th>fermeture</th>
          <th>ouvert</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        <?php
        foreach ($schedules as $schedule) { ?>
          <tr class="schedule-js">
            <td class="hidden--mobile"><?= $schedule->getDay() ?></td>
            <td><input type="time" name="" id="time-open" value="<?= $schedule->getOpen() ?>"></td>
            <td><input type="time" name="" id="time-close" value="<?= $schedule->getClose() ?>"></td>
            <td>
              <label class="switch">
                <input type="checkbox" id="close" <?= (setSwitch($schedule)) ? 'checked' : '' ?>>
                <span class="slider round"></span>
              </label>
            </td>
            <td><button data-schedule-id="<?= $schedule->getId()  ?>" class="table__button">modifier</button></td>
          </tr>
        <?php } ?>
      </tbody>
    </table>
  </div>
</article>
<?php

function setSwitch(Schedule $schedule): bool
{
  if ($schedule->getOpen() || $schedule->getClose()) {
    return true;
  }

  return false;
}
?>