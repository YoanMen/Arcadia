<?php

use App\Model\Schedule;

function setSwitch(Schedule $schedule): bool
{
  if ($schedule->getOpen() || $schedule->getClose()) {
    return true;
  }

  return false;
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= APP_NAME ?>
    | Dashboard - horaire</title>
  <link rel="shortcut icon" href="/assets/images/icons/arcadia-logo.svg" type="image/x-icon">
  <link rel="stylesheet" href="/assets/styles/global.css">
  <link rel="stylesheet" href="/assets/styles/dashboard.css">
  <link rel="stylesheet" href="/assets/styles/pagination/pagination.css">
</head>

<body>

  <?php require_once '../App/View/partials/admin/_adminPanel.php' ?>
  <?php require_once '../App/View/partials/admin/_adminTop.php' ?>

  <main class="dashboard">
    <div class="dashboard__container">
      <?php include_once  '../App/View/partials/_success.php' ?>
      <?php include_once   '../App/View/partials/_error.php' ?>
      <h1 class="dashboard__title ">Horaire</h1>

      <div class="dashboard__content">
        <table aria-describedby="schedules table">
          <thead>
            <tr>
              <th>
                Jours
              </th>
              <th>
                Heure d'ouverture
              </th>
              <th>
                Heure de fermeture
              </th>
              <th>Fermé</th>
            </tr>
          </thead>
          <tbody>
            <?php
            if (isset($data['schedules'])) {
              foreach ($data['schedules'] as $schedule) { ?>
                <tr class="schedule-js">
                  <td><?= $schedule->getDay() ?></td>
                  <td>
                    <input type="hidden" id="csrf_token" name="csrf_token" value='<?= $_SESSION['csrf_token'] ?>'>
                    <input type="time" name="" id="time-open" value="<?= $schedule->getOpen() ?>">
                  </td>
                  <td><input type="time" name="" id="time-close" value="<?= $schedule->getClose() ?>"></td>
                  <td>
                    <label class="switch">
                      <input data-schedule-id="<?= $schedule->getId()  ?>" type="checkbox" id="close" <?= (setSwitch($schedule)) ? 'checked' : '' ?>>
                      <span class="slider round"></span>
                    </label>
                  </td>
                </tr>
            <?php  }
            } ?>
          </tbody>
        </table>
      </div>
    </div>
  </main>

  <script src="/assets/scripts/admin/schedules.js"></script>
  <script src="/assets/scripts/admin/main.js"></script>
</body>

</html>