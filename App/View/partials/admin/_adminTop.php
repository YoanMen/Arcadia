<?php
//top bar for admin page
use App\Core\Security;
?>
<header class="dashboard-top">
  <ul>
    <li>
      <p> <?= Security::getUsername() ?></p>
    </li>
    <li>
      <a href="<?= ROOT ?>/logout">
        <img height="32px" src="<?= ROOT ?>/assets/images/icons/logout.svg" alt="" srcset="">
      </a>
    </li>
  </ul>
</header>