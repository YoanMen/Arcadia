<?php
// @param need $baseUrl
// @param need $currentPage and totalPages


$baseUrl = $_SERVER['REQUEST_URI'];


$baseUrl = preg_replace('/(&[?]page=\d+)/', '', $baseUrl);
$baseUrl = preg_replace('/([?]page=\d+)/', '', $baseUrl);

if (str_contains($baseUrl, '?')) {
  $baseUrl .= "&";
}


if (isset($data['currentPage']) && $data['totalPages']) { ?>
  <nav class="pagination">
    <ul class="pagination__container">
      <li class="pagination__btn--navigation  <?= $data['currentPage'] > 1 ? '' : 'pagination__btn--disabled' ?> ">
        <a class=" button button--cube" href="<?= $baseUrl ?>?page=<?= $data['currentPage'] - 1 ?>">
          <img src="<?= ROOT ?>/assets/images/icons/chevron-left.svg" alt="">
        </a>
      </li>
      <?php

      if ($data['totalPages'] <= 5) {
        for ($i = 1; $i <= $data['totalPages']; $i++) { ?>
          <li class="pagination__btn <?= $data['currentPage'] == $i ? 'pagination__btn--current' : '' ?>  ">
            <a href="<?= $baseUrl ?>?page=<?= $i ?>" class="button button--cube"><?= $i ?></a>
          </li>
        <?php }

        ?>
        <?php } else {
        $showLast = true;
        if ($data['totalPages'] - $data['currentPage'] >= 5) {
          $split = 4;
        } else {
          $split = $data['totalPages'] - $data['currentPage'];
          $showLast = false;
        }

        if ($data['currentPage'] > 2) { ?>
          <li class="pagination__btn">
            <a class="button  button--cube" href="<?= $baseUrl ?>?page=1">1</a>
          </li>
          <li class="pagination__dot ">
            ...
          </li>
        <?php }

        for ($i = 0; $i <= $slipt; $i++) { ?>
          <li class="pagination__btn <?= $data['currentPage'] == $data['currentPage'] + $i ?
                                        'pagination__btn--current' : '' ?>  ">
            <a class="button button--cube" href="<?= $baseUrl ?>?page=<?= $data['currentPage'] + $i ?>">
              <?= $data['currentPage'] + $i ?></a>
          </li>
        <?php }

        if ($showLast) { ?>
          <li class="pagination__dot ">
            ...
          </li>
          <li class="pagination__btn">
            <a class="button  button--cube" href="<?= $baseUrl ?>?page=<?= $data['totalPages'] ?>">
              <?= $data['totalPages'] ?></a>
          </li>

      <?php }
      } ?>
      <li class="pagination__btn--navigation   <?= $data['currentPage'] >= $data['totalPages'] ?
                                                  'pagination__btn--disabled' : '' ?> ">
        <a class="button button--cube" href="<?= $baseUrl ?>?page=<?= $data['currentPage'] + 1 ?>">
          <img src="<?= ROOT ?>/assets/images/icons/chevron-left.svg" alt="">
        </a>
      </li>


    </ul>
  </nav>

<?php } else { ?>
  <p>You need to pass status Page </p>
<?php }
?>