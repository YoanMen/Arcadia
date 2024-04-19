<?php
// need script menu.js

use App\Core\Security;

?>
<header class="menus">
  <!-- MOBILE MENU -->
  <div class="mobile-menu">
    <a href="/public/">
      <img class='mobile-menu__logo' src="/public/assets/images/icons/arcadia-logo.svg" alt="zoo icon">
    </a>
    <div class='mobile-menu__btn'>
      <img src="/public/assets/images/icons/menu.svg" alt="">
    </div>
    <aside class="mobile-menu__tab">
      <div class='mobile-menu__btn-close'>
        <img src="/public/assets/images/icons/close.svg" alt="">
      </div>
      <nav class="mobile-menu__nav" aria-label="mobile menu">
        <ul class="mobile-menu__list">
          <li class="mobile-menu__item">
            <a href="/public/">
              Accueil
            </a>
          </li>
          <li class="mobile-menu__item">
            <a href="/public/services">
              Nos Service
            </a>
            <ul class="mobile-menu__submenu" name='menu-services'>
            </ul>
          </li>
          <li class="mobile-menu__item">
            <a href="/public/habitats">
              Nos habitats
            </a>
            <ul class="mobile-menu__submenu" name='menu-habitats'>

            </ul>
          </li>
          <li class="mobile-menu__item">
            <a href="/public/contact">
              Contact
            </a>
          </li>
          <li>
            <?php if (Security::isLogged()) { ?>
              <a href="/public/dashboard">
                Dashboard
              </a>
            <?php } else { ?>
              <a href="/public/login">
                Connexion
              </a>
            <?php  } ?>
          </li>
        </ul>
      </nav>
    </aside>
  </div>
  <!--END MOBILE MENU -->

  <!-- DESKTOP MENU -->
  <div class="desktop-menu--fixed ">
    <nav class="desktop-menu__nav" aria-label="desktop menu">
      <ul class="desktop-menu__list">
        <li>
          <a href="/public/">
            <img class="desktop-menu__logo" src="<?= ROOT ?>/assets/images/icons/arcadia-logo.svg" alt="arcadia logo">
          </a>
        </li>
        <li>
          <a href="/public/services">
            Nos services
          </a>
          <ul class="desktop-menu__submenu" name='menu-services'>
          </ul>
        </li>
        <li>
          <a href="/public/habitats">
            Nos habitats
          </a>
          <ul class="desktop-menu__submenu" name='menu-habitats'>
          </ul>
        </li>
        <li>
          <a href="/public/contact">
            Contact
          </a>
        </li>
        <li>
          <?php if (Security::isLogged()) { ?>
            <a href="/public/dashboard">
              Dashboard
            </a>
          <?php } else { ?>
            <a href="/public/login">
              Connexion
            </a>
          <?php  } ?>
        </li>
      </ul>
    </nav>
  </div>
  <!-- END DESKTOP MENU-->
</header>