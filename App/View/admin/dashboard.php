<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= APP_NAME ?>
    | Dashboard</title>
  <link rel="stylesheet" href="<?= ROOT ?>/assets/styles/global.css">
  <link rel="stylesheet" href="<?= ROOT ?>/assets/styles/dashboard.css">

</head>

<body>

  <?php require_once '../App/View/partials/admin/_adminPanel.php' ?>
  <?php require_once '../App/View/partials/admin/_adminTop.php' ?>


  <main class="dashboard">
    <div class="dashboard__container">
      <article class="dashboard__element">
        <h2 class="element__title">Les animaux les plus consulté</h2>
        <canvas class="element__chart" id="chart"></canvas>
      </article>
      <article name="habitat-detail" class="dashboard__element">
        <h2 class="element__title">Détails sur l'état des habitats</h2>
        <form class="element__search" action="POST">
          <input placeholder="Tapez le nom recherché" class="" type="search" name="name" id="">
        </form>
        <div class=" element__table">
          <table>
            <thead>
              <tr>
                <th scope="name">nom</th>
                <th>détails</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td scope="name">marais</td>
                <td class="test"><button class="table__button">voir</button></td>
              </tr>
              <tr>
                <td scope="name">jungle</td>
                <td class="test"><button class="table__button">voir</button></td>
              </tr>


            </tbody>
          </table>
        </div>
      </article>
      <article name="animal-report" class="dashboard__element">
        <h2 class="element__title">Rapport sur les animaux</h2>
        <div class="element__table">
          <table>
            <thead>
              <tr>
                <th scope="name">nom</th>
                <th>détails</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td scope="name">marais</td>
                <td class="test"><button class="table__button">voir</button></td>
              </tr>
              <tr>
                <td scope="name">jungle</td>
                <td class="test"><button class="table__button">voir</button></td>
              </tr>


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

</body>

</html>