<?php
// @param $title title card
// @param $text text container
// @param $redirection url for redirection of button
// @param $textBtn text in button
// @param $text in button
// @param $dataId in button

?>

<li class='interactive-card__item' style="background-image: url('/uploads/<?= $pathImg ?>');">
  <div class="interactive-card__content">
    <h3 class='interactive-card__title'><?= $title ?></h3>
    <a <?= $dataId ?? '' ?> href="<?= $redirection ?>" class="button interactive-card__button"> <?= $textBtn ?></a>
  </div>
</li>