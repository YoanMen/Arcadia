<?php
// @param $title title card
// @param $path link to the redirect page
// @param $textBtn text button
?>
<div class="btn-card">
  <h3 class="btn-card__title"><?= $title; ?></h3>
  <a class="btn-card__btn button" href="<?= $path ?>"><?= $textBtn; ?></a>
</div>