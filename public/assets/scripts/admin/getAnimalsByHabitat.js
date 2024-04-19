const habitatSelect = document.getElementById("habitat");
const animalSelect = document.getElementById("animal");

fetchAnimals();

habitatSelect.addEventListener("change", () => {
  fetchAnimals();
});

// fetch animals depending habitat selected
async function fetchAnimals() {
  const id = habitatSelect.value;

  const r = await fetch(`/public/api/habitats/${id}/animals`, {
    method: "GET",
    headers: {
      Accept: "application/json",
    },
  }).then(async (data) => {
    let animals = await data.json();
    let animalsOption = "";

    if (animals) {
      if (animalSelect.disabled) {
        animalSelect.disabled = false;
      }
      animals.forEach((animal) => {
        animalsOption += `<option value="${animal.id}">${animal.name} - ${animal.race}</option>`;
      });

      animalSelect.innerHTML = animalsOption;
    } else {
      animalSelect.disabled = true;
      animalSelect.innerHTML = `<option>aucun animal</option>`;
    }
  });
}
