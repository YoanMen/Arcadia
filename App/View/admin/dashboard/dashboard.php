<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="<?= $_SESSION['csrf_token'] ?>">
  <title><?= APP_NAME ?>
    | Dashboard</title>

  <link rel="icon" href="/assets/images/icons/arcadia-logo.svg" type="image/x-icon">
  <link rel="stylesheet" href="/assets/styles/global.css">
  <link rel="stylesheet" href="/assets/styles/dashboard.css">
  <link rel="stylesheet" href="/assets/styles/dialog.css">
  <link rel="stylesheet" href="/assets/styles/div/details.css">
  <link rel="stylesheet" href="/assets/styles/div/chart.css">
</head>

<body>
  <?php require_once '../App/View/partials/admin/_adminPanel.php' ?>
  <?php require_once '../App/View/partials/admin/_adminTop.php' ?>

  <main class="dashboard">
    <div class="admin__container">

      <?php include_once  '../App/View/partials/_success.php' ?>
      <?php include_once   '../App/View/partials/_error.php' ?>

      <div class='admin__top'>
        <?php if (isset($data['famousAnimals'])) { ?>
          <div class="admin__element">
            <h2 class="admin__title">Les animaux les plus consulté</h2>
            <div class="chart" id="chart">
              <div class="chart__text">
                <p id="hight-click"><?= $data['famousAnimals'][0]['click'] ?></p>
                <p><?= round(($data['famousAnimals'][0]['click'] / 2)) ?> </p>
                <p>0</p>
              </div>
              <div class="chart__items">
                <?php
                // create bars for animals
                foreach ($data['famousAnimals'] as $animal) { ?>
                  <article data-click="<?= $animal['click'] ?>" class="chart__item">
                    <div class="chart__item__bar"></div>
                    <p class="chart__item__text"><?= $animal['name'] ?> </p>
                  </article>
                <?php }  ?>
              </div>
            </div>
          </div>
        <?php } ?>
        <div class="admin__element">
          <h2 class="admin__title">Les derniers commentaire sur les habitats</h2>
          <div class="dashboard__content">
            <table aria-describedby="habitat comment table">
              <thead>
                <tr>
                  <th class="hidden--mobile">
                    De
                  </th>
                  <th>
                    Habitat
                  </th>
                  <th class="hidden--mobile">Commentaire</th>
                  <th>Détails</th>
                </tr>
              </thead>
              <tbody>
                <?php if (isset($data['habitatComments'])) {
                  foreach ($data['habitatComments'] as $habitatComment) : ?>
                    <tr>
                      <td class="hidden--mobile">
                        <?= $habitatComment['email'] ?>
                      </td>
                      <td>
                        <?= $habitatComment['habitat'] ?>
                      </td>
                      <td class="hidden--mobile two-line">
                        <?= $habitatComment['comment'] ?>
                      </td>
                      <td>
                        <a href="/dashboard/commentaire-habitats/<?= $habitatComment['id'] ?>/detail">
                          <img height="32px" src="/assets/images/icons/dots-horizontal.svg" alt="edit icon">
                        </a>
                      </td>
                    </tr>
                  <?php endforeach ?>
                <?php } else { ?>
                  <tr>
                    <td>
                      aucun résultat
                    </td>
                  </tr>
                <?php }  ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
      <div class="admin__element ">
        <h2 class="admin__title">Les derniers rapport vétérinaire</h2>
        <div class="dashboard__content">
          <table aria-describedby="report table">
            <thead>
              <tr>
                <th class="hidden--mobile">
                  De
                </th>
                <th>
                  Animal
                </th>
                <th>
                  État
                </th>
                <th class="hidden--mobile">
                  Habitat
                </th>
                <th class="hidden--mobile">Nourriture</th>
                <th class="hidden--mobile">Quantité</th>
                <th>
                  Date
                </th>
                <th>Détails</th>
              </tr>
            </thead>
            <tbody>
              <?php if (isset($data['reportAnimals'])) {
                foreach ($data['reportAnimals'] as $reportAnimal) : ?>
                  <tr>
                    <td class="hidden--mobile">
                      <?= $reportAnimal['email'] ?>
                    </td>
                    <td>
                      <?= $reportAnimal['name'] ?>
                    </td>
                    <td>
                      <?= $reportAnimal['statut'] ?>
                    </td>
                    <td class="hidden--mobile">
                      <?= $reportAnimal['habitat'] ?>
                    </td>
                    <td class="hidden--mobile">
                      <?= $reportAnimal['food'] ?>
                    </td>
                    <td class="hidden--mobile">
                      <?= $reportAnimal['weight'] ?> kg
                    </td>
                    <td>
                      <?= $reportAnimal['date'] ?>
                    </td>
                    <td>
                      <a href="/dashboard/rapport-animaux/<?= $reportAnimal['id'] ?>/detail">
                        <img height="32px" src="/assets/images/icons/dots-horizontal.svg" alt="edit icon">
                      </a>
                    </td>
                  </tr>
                <?php endforeach ?>
              <?php } else { ?>
                <tr>
                  <td>
                    aucun résultat
                  </td>
                </tr>
              <?php }  ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </main>
</body>

<script src="/assets/scripts/admin/chart.js"></script>

</html>