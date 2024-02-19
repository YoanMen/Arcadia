<nav class="breadcrumbs">
  <ol class="breadcrumbs__list">
    <?php foreach ($elements as $element) { ?>
      <li class="breadcrumbs__item"><a href="<?= $element['path'] ?>"><?= $element['name'] ?></a></li>
    <?php
    } ?>
  </ol>
</nav>