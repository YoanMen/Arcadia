<article name="habitat" class="dashboard__element dashboard__element--full-width ">
  <div class="dashboard__element__top">
    <h1>Habitat</h1>
    <button t id="habitat-add" class="button button--cube">+</button>
  </div>
  <form class="dashboard__element__search" action="POST">
    <input id="habitat-search" placeholder="nom de l'habitat" class="" type="search" name="name" id="">
    <input id="habitat-submit" class="button" type="submit" value="rechercher">

  </form>
  <div class="dashboard__element__table">
    <table>
      <thead>
        <tr>
          <th id="habitat-id" class="clickable">id</th>
          <th id="habitat-name" class="clickable hidden--mobile">nom</th>
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
<script type="module" src="<?= ROOT ?>/assets/scripts/admin/habitatTable.js"></script>