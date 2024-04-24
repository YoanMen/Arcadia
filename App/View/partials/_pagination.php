<?php
// create pagination button with value of currentPage and totalPages passed by controller

$baseUrl = $_SERVER['REQUEST_URI'];

// remove old "page" param
$baseUrl = preg_replace('/([&?]page=\d+)/', '', $baseUrl);

// check if url contain params
if (str_contains($baseUrl, '?')) {
  $baseUrl .= "&page=";
} else {
  $baseUrl .= "?page=";
}
// Disable "go to previous page" button if the current page is the first page
if (isset($data['currentPage']) && $data['totalPages']) { ?>
  <nav class="pagination">
    <ul class="pagination__container">
      <li class="pagination__btn--navigation  <?= $data['currentPage'] > 1 ? '' : 'pagination__btn--disabled' ?> ">
        <a aria-label="btn go to previous page" class=" button button--cube" href="<?= $baseUrl ?><?= $data['currentPage'] - 1 ?>">
          <img src="/assets/images/icons/chevron-left.svg" alt="">
        </a>
      </li>
      <?php

      // if total pages < 5 show all buttons
      if ($data['totalPages'] <= 5) {
        for ($i = 1; $i <= $data['totalPages']; $i++) { ?>
          <li class="pagination__btn <?= $data['currentPage'] == $i ? 'pagination__btn--current' : '' ?>  ">
            <a aria-label="btn go to page <?= $i ?> " href="<?= $baseUrl ?><?= $i ?>" class="button button--cube"><?= $i ?></a>
          </li>
        <?php }
        // if total pages > 5 split pagination with '...' and show last button
      } else {
        $showLast = true;

        // Determine the number of page links to display based on total pages and current page, with an adjustment for optimal pagination experience.
        if ($data['totalPages'] - $data['currentPage'] >= 5) {
          // When there are at least 5 remaining pages after the current one, show a standard number of page links (4 in this case).
          $split = 4;
        } else {
          // Otherwise, calculate the split for page links based on the total number of pages and the current page.
          $split = $data['totalPages'] - $data['currentPage'];
          $showLast = false;
        }
        // Display pagination buttons for navigating through pages, excluding the first page if it's not fully
        if ($data['currentPage'] > 2) { ?>
          <li class="pagination__btn">
            <a class="button  button--cube" href="<?= $baseUrl ?>1">1</a>
          </li>
          <li class="pagination__dot ">
            ...
          </li>
        <?php }
        // Loop through the total number of pages minus 2 (to exclude first and last page buttons)
        for ($i = 0; $i <= $split; $i++) { ?>
          <li class="pagination__btn <?= $data['currentPage'] == $data['currentPage'] + $i ? 'pagination__btn--current' : '' ?>  ">
            <a aria-label="btn go to page <?= $data['currentPage'] + $i ?> " class="button button--cube" href="<?= $baseUrl ?><?= $data['currentPage'] + $i ?>">
              <?= $data['currentPage'] + $i ?>
            </a>
          </li>
        <?php }
        // Display the last page link if it's visible and within pagination range
        if ($showLast) { ?>
          <li class="pagination__dot ">
            ...
          </li>
          <li class="pagination__btn">
            <a aria-label="btn go to page <?= $data['totalPages'] + $i ?> " class="button  button--cube" href="<?= $baseUrl ?><?= $data['totalPages'] ?>">
              <?= $data['totalPages'] ?></a>
          </li>
      <?php }
      }
      // Disable "go to next page" button if the current page is at or beyond the last page
      ?>
      <li class="pagination__btn--navigation   <?= $data['currentPage'] >= $data['totalPages'] ? 'pagination__btn--disabled' : '' ?> ">
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