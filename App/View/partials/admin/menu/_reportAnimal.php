<article name="habitatComment" class="dashboard__element dashboard__element--full-width ">
  <div class="dashboard__element__top">
    <h1 class="hidden--mobile">Détails sur les rapports d'animaux</h1>
    <button t id="animalReport-add" class="button max-width--mobile">
      Ajouter
    </button>
  </div>
  <form class="dashboard__element__search" action="POST">
    <input id="animalReport-dateTime" type="date" name="" id="">
    <input id="animalReport-search" placeholder="nom ou race de l'animal" class="" type="search" name="name" id="">
    <input id="animalReport-submit" class="button" type="submit" value="rechercher">
  </form>
  <div class="dashboard__element__table">
    <table aria-describedby="table for food animals">
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