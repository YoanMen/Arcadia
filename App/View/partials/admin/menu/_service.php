<article name="service" class="dashboard__element dashboard__element--full-width ">
  <div class="dashboard__element__top">
    <h1>Services</h1>
    <button t id="service-add" class="button button--cube">
      <svg fill="white" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
        <path d="M19,13H13V19H11V13H5V11H11V5H13V11H19V13Z" />
      </svg>
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