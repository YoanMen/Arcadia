<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="<?= $_SESSION['csrf_token'] ?>">

  <title><?= APP_NAME ?>
    | Dashboard</title>
  <link rel="stylesheet" href="<?= ROOT ?>/assets/styles/global.css">
  <link rel="stylesheet" href="<?= ROOT ?>/assets/styles/dashboard.css">
  <link rel="stylesheet" href="<?= ROOT ?>/assets/styles/dialog.css">

</head>

<body>

  <?php require_once '../App/View/partials/admin/_adminPanel.php' ?>
  <?php require_once '../App/View/partials/admin/_adminTop.php' ?>


  <dialog class='dialog--admin'>
    <button class='dialog__close button button--cube'>
      <svg fill='white' xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
        <path d="M19,6.41L17.59,5L12,10.59L6.41,5L5,6.41L10.59,12L5,17.59L6.41,19L12,13.41L17.59,19L19,17.59L13.41,12L19,6.41Z" />
      </svg>
    </button>
    <div id="dialog-content" class="dialog__content"></div>


  </dialog>

  <main class="dashboard">
    <div class="dashboard__container">
      <article class="dashboard__element">
        <h2 class="element__title">Les animaux les plus consulté</h2>
        <canvas class="element__chart" id="chart"></canvas>
      </article>
      <article name="habitat-detail" class="dashboard__element">
        <h2 class="element__title">Détails sur l'état des habitats</h2>
        <form class="element__search" action="POST">
          <input id="habitatDetails-search" placeholder="Tapez le nom recherché" class="max-width " type="search" name="name" id="">
          <input id="habitatDetails-submit" class="button" type="submit" value="rechercher">
        </form>
        <div class=" element__table">
          <table>
            <thead>
              <tr>
                <th id='habitatDetails-name' class="clickable">nom</th>
                <th>détails</th>
              </tr>
            </thead>
            <tbody id="habitatDetails-tbody">
              <?php require  "../App/View/partials/_loadingTable.php" ?>
            </tbody>
          </table>
        </div>
      </article>
      <article name="animal-report" class="dashboard__element">
        <h2 class="element__title">Rapport sur les animaux</h2>
        <form class="element__search" action="POST">
          <input type="date" name="" id="">
          <input id="animalReport-search" placeholder="Tapez le nom recherché" class="" type="search" name="name" id="">
          <input id="animalReport-submit" class="button" type="submit" value="rechercher">
        </form>
        <div class="element__table">
          <table>
            <thead>
              <tr>
                <th id="animalReport-name" class="clickable">nom</th>
                <th id="animalReport-statut" class="hidden--mobile clickable">état</th>
                <th id="animalReport-food" class="hidden--mobile clickable">nourriture</th>
                <th id="animalReport-weight" class="hidden--mobile clickable">poids</th>
                <th id="animalReport-date"> date </th>
                <th>détails</th>
              </tr>
            </thead>
            <tbody id="animalReport-tbody">
              <?php require  "../App/View/partials/_loadingTable.php" ?>
            </tbody>
          </table>
        </div>
      </article>
    </div>

  </main>


  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script>
    const ctx = document.getElementById('chart');

    new Chart(ctx, {
      type: 'bar',
      data: {
        labels: ['name', 'name', 'name', 'name', 'name', 'name'],
        datasets: [{
          label: 'nombres de consultations',
          data: [12, 19, 3, 5, 2, 3],
          borderWidth: 1,
          backgroundColor: '#203c25',
        }]
      },
      options: {
        scales: {
          y: {
            beginAtZero: true
          }
        }
      }
    });
  </script>

  <script type="module" src="<?= ROOT ?>/assets/scripts/admin/habitatDetailsTable.js"></script>
  <script type="module" src="<?= ROOT ?>/assets/scripts/admin/animalReportTable.js"></script>

</body>

</html>