<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= APP_NAME ?>
    | Dashboard - animaux</title>
  <link rel="stylesheet" href="<?= ROOT ?>/assets/styles/global.css">
  <link rel="stylesheet" href="<?= ROOT ?>/assets/styles/dashboard.css">
  <link rel="stylesheet" href="<?= ROOT ?>/assets/styles/dialog.css">
  <link rel="stylesheet" href="<?= ROOT ?>/assets/styles/alert.css">
  <link rel="stylesheet" href="<?= ROOT ?>/assets/styles/div/details.css">
  <link rel="stylesheet" href="<?= ROOT ?>/assets/styles/pagination/pagination.css">
</head>

<body>
  <?php require_once '../App/View/partials/admin/_adminPanel.php' ?>
  <?php require_once '../App/View/partials/admin/_adminTop.php' ?>

  <main class="dashboard">
    <div class="dashboard__container">
      <?php include_once  '../App/View/partials/_success.php' ?>
      <?php include_once   '../App/View/partials/_error.php' ?>
      <div class="dashboard__container__top">
        <h1 class="dashboard__title ">Ajouter alimentation pour un animal</h1>
        <button form="add" class="button max-width--mobile">
          <span>Ajouter</span>
        </button>
      </div>
      <div class="dashboard__content">
        <form enctype="multipart/form-data" id='add' method="post">
          <input type="hidden" name="csrf_token" value='<?= $_SESSION['csrf_token'] ?>'>
          <ul>
            <li class="details__item">
              <label for='food'>nourriture</label>
              <input name="food" required minlength="3" maxlength="40" class="details__input" type="text" id="food" value="<?= $data['food'] ?>">
            </li>
            <li class="details__item">
              <label for='quantity'>poids en grammes</label>
              <input name="quantity" required step="0.01" class="details__input" type="number" id="quantity" value="<?= $data['quantity'] ?>">
            </li>
            <li class="details__item">
              <label for='habitat'>habitat</label>
              <select name="habitat" id="habitat">
                <?php if (isset($data['habitats'])) {
                  foreach ($data['habitats'] as $habitat) { ?>
                    <option value="<?= $habitat['id'] ?>"><?= $habitat['name']  ?></option>
                <?php   }
                } ?>
              </select>
            </li>
            <li class="details__item">
              <label for='animals'>animal</label>
              <select disabled name="animal" id="animal">
                <option>aucun animal</option>
              </select>
            </li>
            <li class="details__item">
              <label for='date'>date</label>
              <div class="details__input-wrapper">
                <input name="date" required class="details__input" type="date" id="date" value="<?= $data['date'] ?>">
                <label class="hidden--mobile" for='time'>heure</label>
                <input name="time" required class="details__input" type="time" id="time" value="<?= $data['time'] ?>">
              </div>
            </li>
          </ul>
        </form>
      </div>
    </div>
  </main>
  <script>
    const habitatSelect = document.getElementById('habitat');
    const animalSelect = document.getElementById('animal');

    fetchAnimals();

    habitatSelect.addEventListener('change', () => {

      fetchAnimals();
    })

    async function fetchAnimals() {

      const id = habitatSelect.value;

      const r = await fetch(`/public/api/habitats/${id}/animals`, {
        method: 'GET',
        headers: {
          Accept: "application/json",
        },
      }).then(async (data) => {
        let animals = await data.json();
        let animalsOption = '';

        if (animals) {
          if (animalSelect.disabled) {
            animalSelect.disabled = false;
          }
          animals.forEach(animal => {
            animalsOption += `<option value="${animal.id}">${animal.name} - ${animal.race}</option>`;
          });

          animalSelect.innerHTML = animalsOption;
        } else {
          animalSelect.disabled = true;
          animalSelect.innerHTML = `<option>aucun animal</option>`;
        }
      })

    }
  </script>
</body>

</html>