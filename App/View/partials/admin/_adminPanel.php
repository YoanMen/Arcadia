<?php

use App\Core\Security;
?>
<aside class="dashboard-panel">
  <a class="dashboard__logo" href="<?= ROOT ?>/">
    <img height="84px" src="<?= ROOT ?>/assets/images/icons/arcadia-logo.svg" alt="" srcset="">
  </a>
  <ul class="dashboard-panel__list">
    <?php
    if (Security::isVeterinary()) { ?>
      <li class="dashboard-panel__list__item">
        <a href=""> <img height="32px" src="<?= ROOT ?>/assets/images/icons/viewDashboard.svg" alt="" srcset="">
          <span for="">commentaire sur les habitats</span></a>
      </li>
      <li class="dashboard-panel__list__item">
        <a href=""> <img height="32px" src="<?= ROOT ?>/assets/images/icons/home.svg" alt="" srcset="">
          <span for="">rapport sur les animaux</span> </a>
      </li>
      <li class="dashboard-panel__list__item">
        <a href=""> <img height="32px" src="<?= ROOT ?>/assets/images/icons/paw.svg" alt="" srcset="">
          <span for="">alimentation</span> </a>
      </li>
    <?php }
    if (Security::isEmployee()) { ?>
      <li class="dashboard-panel__list__item">
        <a href=""> <img height="32px" src="<?= ROOT ?>/assets/images/icons/viewDashboard.svg" alt="" srcset="">
          <span for="">avis</span></a>
      </li>
      <li class="dashboard-panel__list__item">
        <a href=""> <img height="32px" src="<?= ROOT ?>/assets/images/icons/home.svg" alt="" srcset="">
          <span for="">services</span> </a>
      </li>
      <li class="dashboard-panel__list__item">
        <a href=""> <img height="32px" src="<?= ROOT ?>/assets/images/icons/paw.svg" alt="" srcset="">
          <span for="">alimentation</span> </a>
      </li>
    <?php }
    if (Security::isAdmin()) { ?>
      <li class="dashboard-panel__list__item">
        <a href=""> <img height="32px" src="<?= ROOT ?>/assets/images/icons/viewDashboard.svg" alt="" srcset="">
          <span for="">dashboard</span></a>
      </li>
      <li class="dashboard-panel__list__item">
        <a href=""> <img height="32px" src="<?= ROOT ?>/assets/images/icons/home.svg" alt="" srcset="">
          <span for="">habitats</span> </a>
      </li>
      <li class="dashboard-panel__list__item">
        <a href=""> <img height="32px" src="<?= ROOT ?>/assets/images/icons/paw.svg" alt="" srcset="">
          <span for="">animaux</span> </a>
      </li>
    <?php } ?>
  </ul>
</aside>