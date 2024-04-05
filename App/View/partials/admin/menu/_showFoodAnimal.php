<article name="foodAnimal" class="dashboard__element dashboard__element--full-width ">
  <div class="dashboard__element__top">
    <h1 class="hidden--mobile">Alimentation des animaux</h1>
  </div>
  <form class="dashboard__element__search" action="POST">
    <input id="search" placeholder="recherche par nom ou habitat" class="" type="search" name="name" id="">
    <input id="submit" class="button" type="submit" value="rechercher">
  </form>
  <div class="dashboard__element__table">
    <table aria-describedby="table for food animals">
      <thead>
        <tr>
          <th id="foodAnimal-name" class="clickable ">nom</th>
          <th id="foodAnimal-habitat" class="clickable hidden--mobile ">habitat</th>
          <th id="foodAnimal-food" class="hidden--mobile">nourriture</th>
          <th id="foodAnimal-food" class="hidden--mobile">quantité</th>
          <th id="foodAnimal-date" class="clickable">date</th>
          <th id="foodAnimal-date" class="hidden--mobile"=>heure</th>
          <th>détails</th>
        </tr>
      </thead>
      <tbody id="foodAnimal-tbody">
        <?php require_once  "../App/View/partials/_loadingTable.php" ?>
      </tbody>
    </table>
  </div>
</article>