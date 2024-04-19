<?php
// aside menu for admin page
use App\Core\Security;
?>
<aside class="dashboard-panel">
  <a class="dashboard__logo" href="/public/">
    <img height="84px" src="/public/assets/images/icons/arcadia-logo.svg" alt="" srcset="">
  </a>
  <nav>
    <ul class="dashboard-panel__list">
      <?php
      // VETERINARY
      if (Security::isVeterinary()) { ?>
        <li class="dashboard-panel__list__item">
          <a href="/public/dashboard/alimentation-animaux">
            <img height="32px" src="/public/assets/images/icons/food-drumstick.svg" alt="" srcset="">
            <span>alimentation des animaux</span> </a>
        </li>
        <li class="dashboard-panel__list__item">
          <a href="/public/dashboard/commentaire-habitats">
            <img height="32px" src="/public/assets/images/icons/chat.svg" alt="" srcset="">
            <span>état des habitats</span></a>
        </li>
        <li class="dashboard-panel__list__item">
          <a id="menu-report" href="/public/dashboard/rapport-animaux">
            <img height="32px" src="/public/assets/images/icons/note.svg" alt="" srcset="">
            <span>rapport des animaux</span> </a>
        </li>
      <?php }
      // EMPLOYEE
      if (Security::isEmployee()) { ?>
        <li class="dashboard-panel__list__item">
          <a href="/public/dashboard/alimentation-animaux">
            <img height="32px" src="/public/assets/images/icons/food-drumstick.svg" alt="" srcset="">
            <span>alimentation des animaux</span> </a>
        </li>
        <li class="dashboard-panel__list__item">
          <a href="/public/dashboard/avis" id="menu-advice">
            <img height="32px" src="/public/assets/images/icons/star.svg" alt="" srcset="">
            <span>avis</span></a>
        </li>
        <li class="dashboard-panel__list__item">
          <a href="/public/dashboard/services">
            <img height="32px" src="/public/assets/images/icons/account-wrench.svg" alt="wrench icon" srcset="">
            <span>services</span> </a>
        </li>
      <?php }
      // ADMIN
      if (Security::isAdmin()) { ?>
        <li class="dashboard-panel__list__item">
          <a href="/public/dashboard" id="menu-dashboard">
            <img height="32px" src="/public/assets/images/icons/viewDashboard.svg" alt="dashboard icon" srcset="">
            <span>dashboard</span></a>
        </li>
        <li class="dashboard-panel__list__item">
          <a href="/public/dashboard/utilisateurs">
            <img height="32px" src="/public/assets/images/icons/account-group.svg" alt="user icon" srcset="">
            <span>utilisateurs</span> </a>
        </li>
        <li class="dashboard-panel__list__item">
          <a href="/public/dashboard/habitats">
            <img height="32px" src="/public/assets/images/icons/home.svg" alt="house icon" srcset="">
            <span>habitats</span>
          </a>
        </li>
        <li class="dashboard-panel__list__item">
          <a href="/public/dashboard/animaux">
            <img height="32px" src="/public/assets/images/icons/paw.svg" alt="paw icon" srcset="">
            <span>animaux</span> </a>
        </li>
        <li class="dashboard-panel__list__item">
          <a href="/public/dashboard/horaires">
            <img height="32px" src="/public/assets/images/icons/clock-outline.svg" alt="clock icon" srcset="">
            <span>horaires</span> </a>
        </li>
        <li class="dashboard-panel__list__item">
          <a href="/public/dashboard/commentaire-habitats">
            <img height="32px" src="/public/assets/images/icons/chat.svg" alt="" srcset="">
            <span>état des habitats</span></a>
        </li>
        <li class="dashboard-panel__list__item">
          <a href="/public/dashboard/rapport-animaux">
            <img height="32px" src="/public/assets/images/icons/note.svg" alt="" srcset="">
            <span>rapport des animaux</span> </a>
        </li>
        <li class="dashboard-panel__list__item">
          <a href="/public/dashboard/services">
            <img height="32px" src="/public/assets/images/icons/account-wrench.svg" alt="wrench icon" srcset="">
            <span>services</span> </a>
        </li>
      <?php } ?>
    </ul>
  </nav>

</aside>