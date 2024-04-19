<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= APP_NAME ?>
    | Dashboard - habitats</title>
  <link rel="stylesheet" href="/public/assets/styles/global.css">
  <link rel="stylesheet" href="/public/assets/styles/dashboard.css">
  <link rel="stylesheet" href="/public/assets/styles/dialog.css">
  <link rel="stylesheet" href="/public/assets/styles/alert.css">
  <link rel="stylesheet" href="/public/assets/styles/div/details.css">
  <link rel="stylesheet" href="/public/assets/styles/pagination/pagination.css">
</head>

<body>
  <?php require_once '../App/View/partials/admin/_adminPanel.php' ?>
  <?php require_once '../App/View/partials/admin/_adminTop.php' ?>

  <main class="dashboard">
    <div class="dashboard__container">
      <?php include_once  '../App/View/partials/_success.php' ?>
      <?php include_once   '../App/View/partials/_error.php' ?>

      <div class="dashboard__container__top">
        <h1 class="dashboard__title ">Modification habitat</h1>
        <button form="edit" class="button max-width--mobile">
          <span>Modifier</span>
        </button>
      </div>
      <div class="dashboard__content">
        <ul>
          <li class="details__item ">
            <label for="habitatImage">ajouter une image</label>
            <div class="max-width ">
              <form class="details__input-wrapper" id="form-image" enctype="multipart/form-data">
                <input max-file-size="<?= MAX_FILE_SIZE ?>" id="image-input" class="details__input" type="file" id="file" id="file" accept="image/png, image/jpeg, image/webp">
                <button data-habitat-id='<?= $data['habitat']->getId() ?>' id="add-image" disabled form="form-image" class="button max-width--mobile">ajouter</button>
              </form>
            </div>
          </li>
          <form id='edit' method="post">
            <input id="csrf_token" type="hidden" name="csrf_token" value='<?= $_SESSION['csrf_token'] ?>'>
            <li class="details__item">
              <label for='name'>nom</label>
              <input name="name" required minlength="3" maxlength="60" class="details__input" type="text" id="email" value="<?= $data['habitat']->getName() ?>">
            </li>
            <li class="details__item">
              <label for='description'>description</label>
              <textarea class="max-width" required name="description" id="description" cols="30" rows="10"><?= $data['habitat']->getDescription() ?></textarea>
            </li>
          </form>
          <?php if (isset($data['images'])) {
            foreach ($data['images'] as $image) { ?>
              <li class="details__item">
                <label for="details__image">image</label>
                <div class="details__input-wrapper">
                  <img class="details__image" width="320px" src="<?= ROOT ?>/uploads/<?= $image['path'] ?>">
                  <p class="details__input"><?= $image['path'] ?></p>
                  <button data-image-id='<?= $image['id'] ?>' class="button button--cube button--red max-width--mobile delete-img-js">
                    <svg height='32px' fill='white' xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                      <path d="M19,4H15.5L14.5,3H9.5L8.5,4H5V6H19M6,19A2,2 0 0,0 8,21H16A2,2 0 0,0 18,19V7H6V19Z" />
                    </svg>
                  </button>
                </div>
              </li> <?php  }
                } ?>
        </ul>
      </div>
    </div>
  </main>
  <script src="<?= ROOT ?>/assets/scripts/admin/habitatImages.js"></script>
</body>

</html>