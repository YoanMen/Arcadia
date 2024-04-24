<?php
// need script menu.js

use App\Core\Security;

?>
<header class="menus">
  <!-- MOBILE MENU -->
  <div class="mobile-menu">
    <a aria-label="home" href="/">
      <img class='mobile-menu__logo' src="/assets/images/icons/arcadia-logo.svg" alt="zoo icon">
    </a>
    <div class='mobile-menu__btn'>
      <img src="/assets/images/icons/menu.svg" alt="">
    </div>
    <aside class="mobile-menu__tab">
      <div class='mobile-menu__btn-close'>
        <img src="/assets/images/icons/close.svg" alt="">
      </div>
      <nav class="mobile-menu__nav" aria-label="mobile menu">
        <ul class="mobile-menu__list">
          <li class="mobile-menu__item">
            <a aria-label="home" href="/">
              Accueil
            </a>
          </li>
          <li class="mobile-menu__item">
            <a href="/services">
              Nos Service
            </a>
            <ul class="mobile-menu__submenu" name='menu-services'>
            </ul>
          </li>
          <li class="mobile-menu__item">
            <a href="/habitats">
              Nos habitats
            </a>
            <ul class="mobile-menu__submenu" name='menu-habitats'>

            </ul>
          </li>
          <li class="mobile-menu__item">
            <a href="/contact">
              Contact
            </a>
          </li>
          <li>
            <?php if (Security::isLogged()) { ?>
              <a href="/dashboard">
                Dashboard
              </a>
            <?php } else { ?>
              <a href="/login">
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
          <a aria-label="home" href="/">
            <img class="desktop-menu__logo" src="/assets/images/icons/arcadia-logo.svg" alt="arcadia logo">
          </a>
        </li>
        <li>
          <a href="/services">
            Nos services
          </a>
          <ul class="desktop-menu__submenu" name='menu-services'>
          </ul>
        </li>
        <li>
          <a href="/habitats">
            Nos habitats
          </a>
          <ul class="desktop-menu__submenu" name='menu-habitats'>
          </ul>
        </li>
        <li>
          <a href="/contact">
            Contact
          </a>
        </li>
        <li>
          <?php if (Security::isLogged()) { ?>
            <a href="/dashboard">
              Dashboard
            </a>
          <?php } else { ?>
            <a href="/login">
              Connexion
            </a>
          <?php  } ?>
        </li>
      </ul>
    </nav>
  </div>
  <!-- END DESKTOP MENU-->
</header>