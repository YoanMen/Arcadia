<?php

// need TotalPages and currentPage variables.

$baseUrl = $_SERVER['REQUEST_URI'];

// remove old "page" param
$baseUrl = preg_replace('/([&?]page=\d+)/', '', $baseUrl);

// check if url contain params
if (str_contains($baseUrl, '?')) {
  $baseUrl .= "&page=";
} else {
  $baseUrl .= "?page=";
}

if (isset($data['currentPage']) && $data['totalPages']) { ?>
  <nav class="pagination">
    <ul class="pagination__container">
      <li class="pagination__btn--navigation  <?= $data['currentPage'] > 1 ? '' : 'pagination__btn--disabled' ?> ">
        <a aria-label="btn go to previous page" class=" button button--cube" href="<?= $baseUrl ?><?= $data['currentPage'] - 1 ?>">
          <img src="/assets/images/icons/chevron-left.svg" alt="">
        </a>
      </li>
      <?php
      // if totalPages is < 5 create button for pages
      if ($data['totalPages'] <= 5) {
        for ($i = 1; $i <= $data['totalPages']; $i++) { ?>
          <li class="pagination__btn <?= $data['currentPage'] == $i ? 'pagination__btn--current' : '' ?>  ">
            <a aria-label="btn go to page <?= $i ?> " href="<?= $baseUrl ?><?= $i ?>" class="button button--cube"><?= $i ?></a>
          </li>
        <?php }  ?>
        <?php
        // if totalPages is > 5 add '...' and lastPage button
      } else {
        $showLast = true;
        if ($data['totalPages'] - $data['currentPage'] >= 5) {
          $split = 4;
        } else {
          $split = $data['totalPages'] - $data['currentPage'];
          $showLast = false;
        }
        // if currentPage > 2 create button to go first page
        if ($data['currentPage'] > 2) { ?>
          <li class="pagination__btn">
            <a class="button  button--cube" href="<?= $baseUrl ?>1">1</a>
          </li>
          <li class="pagination__dot ">
            ...
          </li>
        <?php }
        // displays pagination buttons for each page up to the current page
        for ($i = 0; $i <= $split; $i++) { ?>
          <li class="pagination__btn <?= $data['currentPage'] == $data['currentPage'] + $i ?
                                        'pagination__btn--current' : '' ?>  ">
            <a aria-label="btn go to page <?= $data['currentPage'] + $i ?> " class="button button--cube" href="<?= $baseUrl ?><?= $data['currentPage'] + $i ?>">
              <?= $data['currentPage'] + $i ?>
            </a>
          </li>
        <?php }
        // check if do  show last page button
        if ($showLast) { ?>
          <li class="pagination__dot ">
            ...
          </li>
          <li class="pagination__btn">
            <a aria-label="btn go to page <?= $data['totalPages'] + $i ?> " class="button  button--cube" href="<?= $baseUrl ?><?= $data['totalPages'] ?>">
              <?= $data['totalPages'] ?></a>
          </li>
      <?php }
      } ?>
      <li class="pagination__btn--navigation   <?= $data['currentPage'] >= $data['totalPages'] ?
                                                  'pagination__btn--disabled' : '' ?> ">
        <a aria-label="go to next page" class="button button--cube" href="<?= $baseUrl ?><?= $data['currentPage'] + 1 ?>">
          <img src="/assets/images/icons/chevron-left.svg" alt="">
        </a>
      </li>
    </ul>
  </nav>

<?php } else { ?>
  <p>You need to pass totalPages and currentPage</p>
<?php }
?>