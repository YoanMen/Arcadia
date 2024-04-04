import { FlashMessage } from "../flashMessage.js";
import { FoodAnimalTable } from "./table/foodAnimalTable.js";

// DOM SECTION
const add = document.getElementById("foodAnimal-add");
const tbody = document.getElementById("foodAnimal-tbody");
const dialogConfirm = document.querySelector(".dialog--confirm");
const dashboardContent = document.getElementById("dashboard-content");
// SEARCH
const searchInput = document.getElementById("search");
const submitButton = document.getElementById("submit");

// Order Column
const name = document.getElementById("foodAnimal-name");
const habitat = document.getElementById("foodAnimal-habitat");
const date = document.getElementById("foodAnimal-date");

// END DOM

const table = new FoodAnimalTable();

let response = null;
let count = 0;
let lastScrollPosition = 0;
let isLoading = false;

let detailPanel = "";

let order = "ASC";
let orderBy = "date";

// Ajout : Récupérer la liste des habitat, quand un habitat est
// séléctioner récupéré la liste des animaux lié

getData();
listeners();

function listeners() {
  tbody.addEventListener("scroll", scrollListener);

  name.addEventListener("click", () => {
    getDataWithParams("name");
  });

  habitat.addEventListener("click", () => {
    getDataWithParams("habitat");
  });

  date.addEventListener("click", () => {
    getDataWithParams("date");
  });

  submitButton.addEventListener("click", (event) => {
    event.preventDefault();
    getDataWithParams("id", "ASC");
  });

  add.addEventListener("click", () => {
    openNew();
  });
}

/**
 * get data of table
 */
async function getData() {
  // show loading icon
  table.showLoading(tbody);
  isLoading = true;

  try {
    let newResponse = await table.fetchData("/api/food", "POST", {
      search: searchInput.value,
      order: order,
      orderBy: orderBy,
      count: count,
    });

    if (newResponse.data != null) {
      updateContent(newResponse);
    } else {
      tbody.innerHTML = `<tr class='max-height'>
                          <td>vide</td>        
                        </tr>`;
    }
  } catch (error) {
    tbody.innerHTML = `<tr class='max-height'>
                        <td>${error.message}</td>        
                      </tr>`;
  }

  isLoading = false;
}

/**
 * load data with user params value
 */
function getDataWithParams($orderBy, newOrder = null) {
  if (newOrder == null) {
    order = order == "ASC" ? "DESC" : "ASC";
  }
  orderBy = $orderBy;

  resetData();
}

/**
 * update table with table element
 * @newResponse new data of table
 */
function updateContent(newResponse) {
  if (response != null) {
    const oldResponse = response;
    response.data = oldResponse.data.concat(newResponse.data);
  } else {
    response = newResponse;
  }

  // set count of data
  count += newResponse.data.length;

  // insert new data
  tbody.innerHTML = setTableRow(response);

  tbody.scroll(0, lastScrollPosition);

  const buttons = tbody.querySelectorAll("button");

  buttons.forEach((button) => {
    // listener for habitat detail
    button.addEventListener("click", (event) => {
      const id = button.dataset.foodId;
      const data = response.data.find((data) => data.id == id);

      openDetails(data);
    });
  });
}

/**
 * open new panel
 */
function openNew() {
  document.body.classList.add("no-scroll");
  detailPanel = document.createElement("div");
  detailPanel.classList.add("details");
  detailPanel.name = "outer-details";

  dashboardContent.appendChild(detailPanel);

  detailPanel.innerHTML = table.createNewContent(response.habitats);

  const create = document.getElementById("create");
  const form = document.getElementById("createForm");
  const closeButton = document.getElementById("new-close");

  const foodInput = document.getElementById("food");
  const quantityInput = document.getElementById("quantity");
  const dateInput = document.getElementById("date");
  const timeInput = document.getElementById("time");

  const habitatSelectInput = document.getElementById("habitat-select");
  const animalSelectInput = document.getElementById("animal-select");

  create.addEventListener("click", async (event) => {
    if (form.checkValidity()) {
      event.preventDefault();

      await table
        .fetchData("/api/food/create", "POST", {
          animal_id: animalSelectInput.value,
          food: foodInput.value,
          quantity: quantityInput.value,
          date: dateInput.value,
          time: timeInput.value,
        })
        .then(() => {
          new FlashMessage("success", "l'alimentation de l'animal à été crée");
          detailPanel.remove();
          document.body.classList.remove("no-scroll");
          resetData();
        })
        .catch((error) => {
          new FlashMessage(
            "error",
            `impossible d'ajouter l'alimentation de l'animal : ${error}`
          );
        });
    }
  });

  detailPanel.addEventListener("click", (event) => {
    if (event.target.name == "outer-details") {
      document.body.classList.remove("no-scroll");
      detailPanel.outerHTML = "";
    }
  });
  closeButton.addEventListener("click", () => {
    document.body.classList.remove("no-scroll");
    detailPanel.outerHTML = "";
  });

  habitatSelectInput.addEventListener("change", async () => {
    animalSelectInput.disabled =
      habitatSelectInput.value !== "0" ? false : true;

    if (!animalSelectInput.disabled) {
      let animals = await table.fetchData("/api/animals/habitats", "POST", {
        id: habitatSelectInput.value,
      });

      animalSelectInput.innerHTML = setAnimalsOption(animals.data);
    } else {
      animalSelectInput.innerHTML = "";
    }
  });

  animalSelectInput.addEventListener("change", () => {
    console.log(animalSelectInput.value);
  });
}
/**
 * open panel detail
 * @habitat data of habitat
 */
async function openDetails(foodAnimal) {
  document.body.classList.add("no-scroll");

  detailPanel = document.createElement("div");
  detailPanel.classList.add("details");
  detailPanel.name = "outer-details";

  dashboardContent.appendChild(detailPanel);

  detailPanel.innerHTML = table.createDetailContent(foodAnimal);

  const closeButton = document.querySelector("#details-close");

  detailPanel.addEventListener("click", (event) => {
    if (event.target.name == "outer-details") {
      document.body.classList.remove("no-scroll");
      detailPanel.outerHTML = "";
    }
  });

  // close detail panel and remove panel element
  closeButton.addEventListener("click", () => {
    document.body.classList.remove("no-scroll");
    detailPanel.outerHTML = "";
  });

  // for uploading more image for habitat
}

/**
 * Check position of scroll in tbody and load more data when scroll is bottom
 */
function scrollListener() {
  if (
    tbody.offsetHeight + tbody.scrollTop >= tbody.scrollHeight &&
    !isLoading &&
    count < response.totalCount
  ) {
    lastScrollPosition = tbody.scrollTop;

    getData();
  }
}

/**
 * reset data
 */
function resetData() {
  count = 0;
  response = null;
  lastScrollPosition = 0;
  getData();
}

/**
 * Make content row
 * @returns returns rows
 */
function setTableRow(newResponse) {
  let rows = ``;
  newResponse.data.forEach((data) => {
    rows += table.createRow(data);
  });
  return rows;
}

function setAnimalsOption(animals) {
  let content = "";

  animals.forEach((animal) => {
    content += `<option value="${animal.id}">${animal.name} -- ${animal.race}</option>`;
  });

  return content;
}
