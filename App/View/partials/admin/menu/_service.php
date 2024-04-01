<article name="service" class="dashboard__element dashboard__element--full-width ">
  <div class="dashboard__element__top">
    <h1 class="hidden--mobile">Services</h1>
    <button t id="service-add" class="button max-width--mobile">
      Ajouter
    </button>
  </div>
  <form class="dashboard__element__search" action="POST">
    <input id="search" placeholder="nom du service" class="" type="search" name="name" id="">
    <input id="submit" class="button" type="submit" value="rechercher">
  </form>
  <div class="dashboard__element__table">
    <table aria-describedby="table for service">
      <thead>
        <tr>
          <th id="service-id" class="clickable hidden--mobile">id</th>
          <th id="service-name" class="clickable ">nom</th>
          <th id="service-description" class="hidden--mobile">description</th>
          <th>détails</th>
        </tr>
      </thead>
      <tbody id="service-tbody">
        <?php require_once  "../App/View/partials/_loadingTable.php" ?>
      </tbody>
    </table>
  </div>
</article>