import { getDataTable, showLoading } from "./table.js";

const dialog = document.querySelector("dialog");
const table = document.getElementById("animalReport-table");

const searchInput = document.getElementById("animalReport-search");
const dateInput = document.getElementById("animalReport-dateTime");
const submitButton = document.getElementById("animalReport-submit");
const name = document.getElementById("animalReport-name");
const race = document.getElementById("animalReport-race");
const date = document.getElementById("animalReport-date");
const tbody = document.getElementById("animalReport-tbody");

let order = "DESC";
let orderBy = "date";
let response = null;
let isLoading = false;
let count = 0;
let lastScrollPosition = 0;
let content = ``;

getData();
listeners();

function listeners() {
  tbody.addEventListener("scroll", scrollListener);

  name.addEventListener("click", () => {
    getDataWithParams("name");
  });

  date.addEventListener("click", () => {
    getDataWithParams("date");
  });

  race.addEventListener("click", () => {
    getDataWithParams("race");
  });

  submitButton.addEventListener("click", (event) => {
    event.preventDefault();
    getDataWithParams("date", "ASC");
  });

  dialog.addEventListener("close", () => {
    document.body.classList.remove("no-scroll");
  });
}

async function getData() {
  showLoading(tbody);
  isLoading = true;

  try {
    let newResponse = await getDataTable("/api/animals/report", "POST", {
      search: searchInput.value,
      date: dateInput.value,
      order: order,
      orderBy: orderBy,
      count: count,
    });

    if (newResponse.error) {
      tbody.innerHTML = `<tr class='max-height'>
                            <td>${newResponse.error}</td>        
                         </tr>`;
      return;
    }

    updateTable(newResponse);
  } catch {
    tbody.innerHTML = `<tr class='max-height'>
                        <td>impossible de récupérer les données</td>        
                       </tr>`;
  }

  isLoading = false;
}

function updateTable(newResponse) {
  if (response != null) {
    const oldResponse = response;
    response.data = oldResponse.data.concat(newResponse.data);
  } else {
    response = newResponse;
  }

  count += newResponse.data.length;

  const th = table.getElementsByTagName("th");
  content = setTableRow(response);

  tbody.innerHTML = content;
  tbody.scroll(0, lastScrollPosition);

  const buttons = tbody.querySelectorAll("button");

  buttons.forEach((button) => {
    button.addEventListener("click", (event) => {
      const id = event.target.getAttribute("id");
      const data = response.data.find((data) => data.id == id);

      showDetails(data);
    });
  });
}

function getDataWithParams($orderBy, newOrder = null) {
  if (newOrder == null) {
    order = order == "ASC" ? "DESC" : "ASC";
  }
  orderBy = $orderBy;

  // RESET
  count = 0;
  content = "";
  response = null;
  lastScrollPosition = 0;
  getData();
}

async function showDetails(data) {
  showDialog(data);

  const closeButton = document.querySelector(".dialog__close");
  closeButton.addEventListener("click", () => {
    dialog.close();
  });
}

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

function setTableRow(newResponse) {
  content = ``;
  newResponse.data.forEach((data) => {
    content += `<tr>
                  <td>${data.animalName} </td>
                  <td class="hidden--mobile">${data.race} </td>
                  <td class="hidden--mobile">${data.statut} </td>
                  <td class="hidden--mobile">${data.food} </td>
                  <td class="hidden--mobile">${data.weight} </td>
                  <td>${data.date} </td>
                  <td><button id="${data.id}" class="table__button">voir</button></td>
                </tr>`;
  });

  return content;
}

function showDialog(data) {
  dialog.innerHTML = `<div class="dialog__top">
                        <h3 class="dialog__title">Rapport sur les animaux</h3>
                        <div class="dialog__top__buttons">
               
                          <button class='dialog__close button button--cube'>
                            <svg fill='white' xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                              <path d="M19,6.41L17.59,5L12,10.59L6.41,5L5,6.41L10.59,12L5,17.59L6.41,19L12,13.41L17.59,19L19,17.59L13.41,12L19,6.41Z" />
                            </svg>
                          </button>
                        </div>
                      </div>
                      <div id="dialog-content" class="dialog__content">
                      <ul class="dialog__list">
                      <li class="dialog__item">
                        <label>nom</label>
                        <p>${data.animalName}</p>
                      </li>
                      <li class="dialog__item">
                        <label>race</label>
                        <p>${data.race}</p>
                      </li>
                      <li class="dialog__item">
                        <label>état</label>
                        <p>${data.statut}</p>
                      </li>
                      <li class="dialog__item">
                        <label>nourriture recommandé</label>
                        <p>${data.food}</p>
                      </li>
                      <li class="dialog__item">
                        <label>poids nourriture</label>
                        <p>${data.weight}g</p>
                      </li>
                      <li class="dialog__item">
                        <label>date</label>
                        <p>${data.date}</p>
                      </li>
                      <li class="dialog__item">
                        <label>détail</label>
                        <p>${data.details}</p>
                      </li>
                      </ul>
                      </div>`;

  dialog.showModal();
  document.body.classList.add("no-scroll");
}
