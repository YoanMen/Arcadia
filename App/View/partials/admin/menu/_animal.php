<article name="animal" class="dashboard__element dashboard__element--full-width ">
  <div class="dashboard__element__top">
    <h1 class="hidden--mobile">Animaux</h1>
    <button t id="animal-add" class="button max-width--mobile">
      Ajouter
    </button>
  </div>
  <form class="dashboard__element__search" action="POST">
    <input id="animal-search" placeholder="nom ou race de l'animal" class="" type="search" name="name" id="">
    <input id="animal-submit" class="button" type="submit" value="rechercher">

  </form>
  <div class="dashboard__element__table">
    <table>
      <thead>
        <tr>
          <th id="animal-id" class="clickable hidden--mobile">id</th>
          <th id="animal-name" class="clickable ">nom</th>
          <th id="animal-race" class="clickable hidden--mobile">race</th>
          <th id="animal-habitat" class="clickable hidden--mobile">habitat</th>
          <th>détails</th>
        </tr>
      </thead>
      <tbody id="animal-tbody">
        <?php require_once "../App/View/partials/_loadingTable.php" ?>
      </tbody>
    </table>
  </div>
</article>