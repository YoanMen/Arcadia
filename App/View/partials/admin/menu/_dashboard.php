<article class="dashboard__element">
  <h2 class="dashboard__element__title">Les animaux les plus consulté</h2>
  <canvas class="dashboard__element__chart" id="chart"></canvas>
</article>
<article name="habitat-detail" class="dashboard__element">
  <h2 class="dashboard__element__title">Détails sur l'état des habitats</h2>
  <form class="dashboard__element__search" action="POST">
    <input id="habitatComment-search" placeholder="nom de l'animal" class="max-width " type="search" name="name" id="">
    <input id="habitatComment-submit" class="button" type="submit" value="rechercher">
  </form>
  <div class="dashboard__element__table">
    <table id='habitatComment-table'>
      <thead>
        <tr>
          <th id='habitatComment-name' class="clickable">nom</th>
          <th>détails</th>
        </tr>
      </thead>
      <tbody id="habitatComment-tbody">
        <?php require  "../App/View/partials/_loadingTable.php" ?>
      </tbody>
    </table>
  </div>
</article>
<article name="animal-report" class="dashboard__element">
  <h2 class="dashboard__element__title">Rapport sur les animaux</h2>
  <form class="dashboard__element__search" action="POST">
    <input id="animalReport-dateTime" type="date" name="" id="">
    <input id="animalReport-search" placeholder="nom ou race de l'animal" class="" type="search" name="name" id="">
    <input id="animalReport-submit" class="button" type="submit" value="rechercher">
  </form>
  <div class="dashboard__element__table">
    <table id='animalReport-table'>
      <thead>
        <tr>
          <th id="animalReport-name" class="clickable">nom</th>
          <th id="animalReport-race" class="clickable hidden--mobile">race</th>
          <th id="animalReport-statut" class="hidden--mobile">état</th>
          <th id="animalReport-food" class="hidden--mobile">nourriture</th>
          <th id="animalReport-weight" class="hidden--mobile">poids</th>
          <th id="animalReport-date" class="clickable"> date </th>
          <th>détails</th>
        </tr>
      </thead>
      <tbody id="animalReport-tbody">
        <?php require  "../App/View/partials/_loadingTable.php" ?>
      </tbody>
    </table>
  </div>
</article>


<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
  const ctx = document.getElementById('chart');

  new Chart(ctx, {
    type: 'bar',
    data: {
      labels: ['name', 'name', 'name', 'name', 'name'],
      datasets: [{
        label: 'nombres de consultations',
        data: [12, 19, 3, 5, 2],
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
<script type="module" src="<?= ROOT ?>/assets/scripts/admin/habitatCommentTable.js"></script>
<script type="module" src="<?= ROOT ?>/assets/scripts/admin/animalReportTable.js"></script>