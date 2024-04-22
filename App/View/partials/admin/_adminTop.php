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
      <a href="/logout">
        <img height="32px" src="/assets/images/icons/logout.svg" alt="logout icon">
      </a>
    </li>
  </ul>
</header>