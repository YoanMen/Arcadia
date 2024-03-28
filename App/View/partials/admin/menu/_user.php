<article name="user" class="dashboard__element dashboard__element--full-width ">
  <div class="dashboard__element__top">
    <h1>Utilisateurs</h1>
    <button t id="user-add" class="button button--cube">
      <svg fill="white" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
        <path d="M19,13H13V19H11V13H5V11H11V5H13V11H19V13Z" />
      </svg>
    </button>
  </div>
  <form class="dashboard__element__search" action="POST">
    <input id="search" placeholder="nom de l'utilisateur" class="" type="search" name="name" id="">
    <input id="submit" class="button" type="submit" value="rechercher">
  </form>
  <div class="dashboard__element__table">
    <table aria-describedby="table for service">
      <thead>
        <tr>
          <th id="user-id" class="clickable hidden--mobile">id</th>
          <th id="user-email" class="clickable ">email</th>
          <th id="user-password" class="hidden--mobile">mot de passe</th>
          <th id="user-role" class="clickable hidden--mobile">rôle</th>

          <th>détails</th>
        </tr>
      </thead>
      <tbody id="user-tbody">
        <?php require_once  "../App/View/partials/_loadingTable.php" ?>
      </tbody>
    </table>
  </div>
</article>