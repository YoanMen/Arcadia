<?php if (isset($_SESSION['error'])) { ?>
  <div class="error">
    <p>
      <?= $_SESSION['error'] ?>
    </p>
  </div>
<?php }
unset($_SESSION['error'])
?>