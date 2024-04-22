<?php
// @param elements contains an array  ['path'=> '/path',
//                                     'name' => 'name']
?>
<nav class="breadcrumbs">
  <ol class="breadcrumbs__list">
    <?php foreach ($elements as $element) { ?>
      <li class="breadcrumbs__item">
        <a aria-label="<?= $element['name'] ?>" href="<?= $element['path'] ?>"><?= $element['name'] ?></a>
      </li>
    <?php
    } ?>
  </ol>
</nav>