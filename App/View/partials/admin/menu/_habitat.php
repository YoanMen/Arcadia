<article name="habitat" class="dashboard__element dashboard__element--full-width ">
  <div class="dashboard__element__top">
    <h1>Habitats</h1>
    <button t id="habitat-add" class="button button--cube">
      <svg fill="white" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
        <path d="M19,13H13V19H11V13H5V11H11V5H13V11H19V13Z" />
      </svg>
    </button>
  </div>
  <form class="dashboard__element__search" action="POST">
    <input id="habitat-search" placeholder="nom de l'habitat" class="" type="search" name="name" id="">
    <input id="habitat-submit" class="button" type="submit" value="rechercher">

  </form>
  <div class="dashboard__element__table">
    <table>
      <thead>
        <tr>
          <th id="habitat-id" class="clickable hidden--mobile">id</th>
          <th id="habitat-name" class="clickable ">nom</th>
          <th id="habitat-description" class="hidden--mobile">description</th>
          <th>détails</th>
        </tr>
      </thead>
      <tbody id="habitat-tbody">
        <?php require  "../App/View/partials/_loadingTable.php" ?>
      </tbody>
    </table>
  </div>
</article>