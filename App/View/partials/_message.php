<?php if (isset($data['error']) && !is_null($data['error'])) { ?>
  <div class="error">
    <p>
      <?= $data['error'] ?>
    </p>
  </div>
<?php } ?>