<?php
// need carousel.js.
// $autoplay bool for auto play.
// $images need array of image path
?>

<div <?= $autoplay ?  'autoplay'  : '' ?> class="carousel">
  <?php if (count($images) > 1) { ?>
    <div class="carousel__container_buttons">
      <div class="carousel__button left-carousel-js ">
        <svg width="64px" fill='white' xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
          <path d="M15.41,16.58L10.83,12L15.41,7.41L14,6L8,12L14,18L15.41,16.58Z" />
        </svg>
      </div>
      <div class="carousel__button right-carousel-js">
        <svg width="64px" fill='white' xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
          <path d="M15.41,16.58L10.83,12L15.41,7.41L14,6L8,12L14,18L15.41,16.58Z" />
        </svg>
      </div>
    </div>
    <div id="carousel-select" class="carousel__select">
    </div>
  <?php } ?>
  <div class="carousel__container">
    <?php
    foreach ($images as $image) { ?>
      <div class="carousel__item">
        <img src="/uploads/<?= $image ?>" alt="carousel content" loading="eager">
      </div>
    <?php } ?>
  </div>
</div>