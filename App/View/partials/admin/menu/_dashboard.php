<article class="dashboard__element">
  <h2 class="dashboard__element__title">Les animaux les plus consulté</h2>
  <div class="chart" id="chart">
    <div class="chart__text">
      <p>30</p>
      <p>15</p>
      <p>0</p>
    </div>
    <div class="chart__items">
      <article class="chart__item">
        <div class="chart__item__bar"></div>
        <p class="chart__item__text one-line">riko</p>
      </article>
      <article class="chart__item">
        <div class="chart__item__bar"></div>
        <p class="chart__item__text one-line">loadzdzqdzdzqdqzd</p>
      </article>
    </div>

  </div>
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
          <th class="hidden--mobile">commentaire</th>
          <th>détails</th>
        </tr>
      </thead>
      <tbody id="habitatComment-tbody">
        <?php require_once  "../App/View/partials/_loadingTable.php" ?>
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
        <?php require_once  "../App/View/partials/_loadingTable.php" ?>
      </tbody>
    </table>
  </div>
</article>