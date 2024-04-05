<article name="habitatComment" class="dashboard__element dashboard__element--full-width ">
  <div class="dashboard__element__top">
    <h1 class="hidden--mobile">Détails sur l'état des habitats</h1>
    <button t id="habitatComment-add" class="button max-width--mobile">
      Ajouter
    </button>
  </div>
  <form class="dashboard__element__search" action="POST">
    <input id="habitatComment-search" placeholder="recherche par nom de l'habitat" class="" type="search" name="name" id="">
    <input id="habitatComment-submit" class="button" type="submit" value="rechercher">
  </form>
  <div class="dashboard__element__table">
    <table aria-describedby="table for food animals">
      <thead>
        <tr>
          <th id="habitatComment-name" class="clickable ">habitat</th>
          <th id="habitatComment-comment" class="hidden--mobile"=>commentaire</th>
          <th>détails</th>
        </tr>
      </thead>
      <tbody id="habitatComment-tbody">
        <?php require_once  "../App/View/partials/_loadingTable.php" ?>
      </tbody>
    </table>
  </div>
</article>