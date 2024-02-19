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
            <a href="">
              Nos Service
            </a>
            <ul class="mobile-menu__submenu">
              <?php if (isset($data['services'])) {
                foreach ($data['services'] as $service) { ?>
                  <li>
                    <a href="service/<?= $service->getId() ?>">
                      <?= $service->getName(); ?>
                    </a>
                  </li>
              <?php }
              } ?>
            </ul>
          </li>
          <li class="mobile-menu__item">
            <a href="">
              Nos habitats
            </a>
            <ul class="mobile-menu__submenu">
              <?php if (isset($data['habitats'])) {
                foreach ($data['habitats'] as $habitat) { ?>
                  <li>
                    <a href="habitat/<?= $habitat->getId() ?>">
                      <?= $habitat->getName(); ?>
                    </a>
                  </li>
              <?php }
              } ?>
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
  <div class="desktop-menu ">
    <nav class="desktop-menu__nav" aria-label="desktop menu">
      <ul class="desktop-menu__list">
        <li>
          <a href="">
            <img class="desktop-menu__logo" src="<?= ROOT ?>/assets/images/icons/arcadia-logo.svg" alt="arcadia logo">
          </a>
        </li>
        <li>
          <a href="#">
            Nos services
          </a>
          <ul class="desktop-menu__submenu">
            <?php if (isset($data['services'])) {
              foreach ($data['services'] as $service) { ?>
                <li>
                  <a href="service/<?= $service->getId() ?>">
                    <?= $service->getName(); ?>
                  </a>
                </li>
            <?php }
            } ?>
          </ul>
        </li>
        <li>
          <a href="#">
            Nos habitats
          </a>
          <ul class="desktop-menu__submenu">
            <?php if (isset($data['habitats'])) {
              foreach ($data['habitats'] as $habitat) { ?>
                <li>
                  <a href="habitat/<?= $habitat->getId() ?>">
                    <?= $habitat->getName(); ?>
                  </a>
                </li>
            <?php }
            } ?>
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