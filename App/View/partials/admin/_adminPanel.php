<?php

use App\Core\Security;
?>
<aside class="dashboard-panel">
  <a class="dashboard__logo" href="<?= ROOT ?>/">
    <img height="84px" src="<?= ROOT ?>/assets/images/icons/arcadia-logo.svg" alt="" srcset="">
  </a>
  <nav>

    <ul class="dashboard-panel__list">
      <?php
      // VETERINARY
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
      // EMPLOYEE
      if (Security::isEmployee()) { ?>
        <li class="dashboard-panel__list__item">
          <a id="menu-foodAnimal" href="#">
            <img height="32px" src="<?= ROOT ?>/assets/images/icons/food-drumstick.svg" alt="" srcset="">
            <span for="">alimentation</span> </a>
        </li>
        <li class="dashboard-panel__list__item">
          <a href="#" id="menu-advice">
            <img height="32px" src="<?= ROOT ?>/assets/images/icons/star.svg" alt="" srcset="">
            <span for="">avis</span></a>
        </li>
      <?php }
      // ADMIN
      if (Security::isAdmin()) { ?>
        <li class="dashboard-panel__list__item">
          <a href="#" id="menu-dashboard">
            <img height="32px" src="<?= ROOT ?>/assets/images/icons/viewDashboard.svg" alt="dashboard icon" srcset="">
            <span for="">dashboard</span></a>
        </li>
        <li class="dashboard-panel__list__item">
          <a id="menu-user" href="#">
            <img height="32px" src="<?= ROOT ?>/assets/images/icons/account-group.svg" alt="user icon" srcset="">
            <span for="">utilisateurs</span> </a>
        </li>
        <li class="dashboard-panel__list__item">
          <a id='menu-habitat' href="#">
            <img height="32px" src="<?= ROOT ?>/assets/images/icons/home.svg" alt="house icon" srcset="">
            <span for="">habitats</span>
          </a>
        </li>
        <li class="dashboard-panel__list__item">
          <a id='menu-animal' href="">
            <img height="32px" src="<?= ROOT ?>/assets/images/icons/paw.svg" alt="paw icon" srcset="">
            <span for="">animaux</span> </a>
        </li>
        <li class="dashboard-panel__list__item">
          <a id='menu-schedule' href="">
            <img height="32px" src="<?= ROOT ?>/assets/images/icons/clock-outline.svg" alt="clock icon" srcset="">
            <span for="">horaires</span> </a>
        </li>
      <?php }
      if (Security::isEmployee() || Security::isAdmin()) { ?>
        <li class="dashboard-panel__list__item">
          <a id="menu-service" href="">
            <img height="32px" src="<?= ROOT ?>/assets/images/icons/account-wrench.svg" alt="wrench icon" srcset="">
            <span for="">services</span> </a>
        </li>
      <?php  } ?>

    </ul>
  </nav>

</aside>