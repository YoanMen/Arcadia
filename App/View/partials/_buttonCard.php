<?php
// @param $title title card
// @param $path link to the redirect page
// @param $textBtn text button
// @param $idName button
?>
<div class="btn-card">
  <h3 class="btn-card__title"><?= $title; ?></h3>
  <a aria-label="<?= $title ?>" class="btn-card__btn button" <?= isset($idName) ? 'id="' . $idName . '"' : '' ?> <?= isset($path) ? 'href="' . $path .  '"' : '' ?>><?= $textBtn; ?></a>
</div>