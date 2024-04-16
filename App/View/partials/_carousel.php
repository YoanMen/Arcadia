<?php
// need carousel.js.
// $autoplay bool for auto play.
// $images need array of image path
?>

<div <?= $autoplay ?  'autoplay'  : '' ?> class="carousel">
  <?php if (count($images) > 1) { ?>
    <div id="carousel-select" class="carousel__select">
    </div>
  <?php } ?>
  <div class="carousel__container">
    <?php
    foreach ($images as $image) { ?>
      <div class="carousel__item">
        <img src="<?= ROOT ?>/uploads/<?= $image ?>" alt="carousel" loading="eager">
      </div>
    <?php } ?>
  </div>
</div>