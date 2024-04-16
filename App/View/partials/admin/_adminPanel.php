<?php
// aside menu for admin page
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
          <a href="<?= ROOT ?>/dashboard/alimentation-animaux">
            <img height="32px" src="<?= ROOT ?>/assets/images/icons/food-drumstick.svg" alt="" srcset="">
            <span>alimentation des animaux</span> </a>
        </li>
        <li class="dashboard-panel__list__item">
          <a href="<?= ROOT ?>/dashboard/commentaire-habitats">
            <img height="32px" src="<?= ROOT ?>/assets/images/icons/chat.svg" alt="" srcset="">
            <span>état des habitats</span></a>
        </li>
        <li class="dashboard-panel__list__item">
          <a id="menu-report" href="<?= ROOT ?>/dashboard/rapport-animaux">
            <img height="32px" src="<?= ROOT ?>/assets/images/icons/note.svg" alt="" srcset="">
            <span>rapport des animaux</span> </a>
        </li>
      <?php }
      // EMPLOYEE
      if (Security::isEmployee()) { ?>
        <li class="dashboard-panel__list__item">
          <a href="<?= ROOT ?>/dashboard/alimentation-animaux">
            <img height="32px" src="<?= ROOT ?>/assets/images/icons/food-drumstick.svg" alt="" srcset="">
            <span>alimentation des animaux</span> </a>
        </li>
        <li class="dashboard-panel__list__item">
          <a href="<?= ROOT ?>/dashboard/avis" id="menu-advice">
            <img height="32px" src="<?= ROOT ?>/assets/images/icons/star.svg" alt="" srcset="">
            <span>avis</span></a>
        </li>
        <li class="dashboard-panel__list__item">
          <a href="<?= ROOT ?>/dashboard/services">
            <img height="32px" src="<?= ROOT ?>/assets/images/icons/account-wrench.svg" alt="wrench icon" srcset="">
            <span>services</span> </a>
        </li>
      <?php }
      // ADMIN
      if (Security::isAdmin()) { ?>
        <li class="dashboard-panel__list__item">
          <a href="<?= ROOT ?>/dashboard" id="menu-dashboard">
            <img height="32px" src="<?= ROOT ?>/assets/images/icons/viewDashboard.svg" alt="dashboard icon" srcset="">
            <span>dashboard</span></a>
        </li>
        <li class="dashboard-panel__list__item">
          <a href="<?= ROOT ?>/dashboard/utilisateurs">
            <img height="32px" src="<?= ROOT ?>/assets/images/icons/account-group.svg" alt="user icon" srcset="">
            <span>utilisateurs</span> </a>
        </li>
        <li class="dashboard-panel__list__item">
          <a href="<?= ROOT ?>/dashboard/habitats">
            <img height="32px" src="<?= ROOT ?>/assets/images/icons/home.svg" alt="house icon" srcset="">
            <span>habitats</span>
          </a>
        </li>
        <li class="dashboard-panel__list__item">
          <a href="<?= ROOT ?>/dashboard/animaux">
            <img height="32px" src="<?= ROOT ?>/assets/images/icons/paw.svg" alt="paw icon" srcset="">
            <span>animaux</span> </a>
        </li>
        <li class="dashboard-panel__list__item">
          <a href="<?= ROOT ?>/dashboard/horaires">
            <img height="32px" src="<?= ROOT ?>/assets/images/icons/clock-outline.svg" alt="clock icon" srcset="">
            <span>horaires</span> </a>
        </li>
        <li class="dashboard-panel__list__item">
          <a href="<?= ROOT ?>/dashboard/commentaire-habitats">
            <img height="32px" src="<?= ROOT ?>/assets/images/icons/chat.svg" alt="" srcset="">
            <span>état des habitats</span></a>
        </li>
        <li class="dashboard-panel__list__item">
          <a href="<?= ROOT ?>/dashboard/rapport-animaux">
            <img height="32px" src="<?= ROOT ?>/assets/images/icons/note.svg" alt="" srcset="">
            <span>rapport des animaux</span> </a>
        </li>
        <li class="dashboard-panel__list__item">
          <a href="<?= ROOT ?>/dashboard/services">
            <img height="32px" src="<?= ROOT ?>/assets/images/icons/account-wrench.svg" alt="wrench icon" srcset="">
            <span>services</span> </a>
        </li>
      <?php } ?>
    </ul>
  </nav>

</aside>