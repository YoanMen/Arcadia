<?php
// need script menu.js
?>

<header class="menus">
  <!-- MOBILE MENU -->
  <div class="mobile-menu">
    <a href="/">
      <img class='mobile-menu__logo' src="<?= ROOT ?>/assets/images/icons/arcadia-logo.svg" alt="">
    </a>
    <div class='mobile-menu__btn'>
      <img src="<?= ROOT ?>/assets/images/icons/menu.svg" alt="">
    </div>
    <aside class="mobile-menu__tab">
      <div class='mobile-menu__btn-close'>
        <img src="<?= ROOT ?>/assets/images/icons/close.svg" alt="">
      </div>
      <nav class="mobile-menu__nav" aria-label="mobile menu">
        <ul class="mobile-menu__list">
          <li class="mobile-menu__item">
            <a href="">
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
            <a href="">
              Nos habitats
            </a>
            <ul class="mobile-menu__submenu" name='menu-habitats'>

            </ul>
          </li>
          <li class="mobile-menu__item">
            <a href="">
              Contact
            </a>
          </li>
          <li class="mobile-menu__item">
            <a href="">
              Connexion
            </a>
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
          <a href="<?= ROOT ?>">
            <img class="desktop-menu__logo" src="<?= ROOT ?>/assets/images/icons/arcadia-logo.svg" alt="arcadia logo">
          </a>
        </li>
        <li>
          <a href="<?= ROOT ?>/services">
            Nos services
          </a>
          <ul class="desktop-menu__submenu" name='menu-services'>
          </ul>
        </li>
        <li>
          <a href="#">
            Nos habitats
          </a>
          <ul class="desktop-menu__submenu" name='menu-habitats'>
          </ul>
        </li>
        <li>
          <a href="#">
            Contact
          </a>
        </li>
        <li>
          <a href="#">
            Connexion
          </a>
        </li>
      </ul>
    </nav>
  </div>
  <!-- END DESKTOP MENU-->
</header>