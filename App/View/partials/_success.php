<?php if (isset($_SESSION['success'])) { ?>
  <div class="success">
    <p>
      <?= $_SESSION['success'] ?>
    </p>
  </div>
<?php }
unset($_SESSION['success'])
?>