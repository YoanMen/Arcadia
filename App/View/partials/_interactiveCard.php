<?php
// @param $title title card
// @param $text text container
// @param $redirection url for redirection of button
// @param $textBtn text in button
?>

<li class='interactive-card__item' style="background-image: url('<?= ROOT ?>/uploads/<?= $pathImg ?>');">
  <div class="interactive-card__content">
    <h3 class='interactive-card__title'><?= $title ?></h3>
    <div class='interactive-card__container'>
      <p class="interactive-card__text"><?= $text ?></p>
      <a href="<?= $redirection ?>" class="button"> <?= $textBtn ?></a>
    </div>
  </div>
</li>