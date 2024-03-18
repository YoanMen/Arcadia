import { getHabitatImages, getHabitats } from "../fetchData.js";
import { showLoading } from "./loading.js";
import { updateHabitat } from "./updateData.js";

let order = "ASC";
let orderBy = "id";
let response = null;

let isLoading = false;
let count = 0;
let lastScrollPosition = 0;
let content = ``;

const dialog = document.querySelector("dialog");
const searchInput = document.querySelector("#habitat-search");
const submitButton = document.querySelector("#habitat-submit");
const id = document.querySelector("#habitat-id");
const name = document.querySelector("#habitat-name");
const tbody = document.querySelector("#habitat-tbody");

getData();

tbody.addEventListener("scroll", () => {
  if (
    tbody.offsetHeight + tbody.scrollTop >= tbody.scrollHeight &&
    !isLoading &&
    count < response.totalCount
  ) {
    lastScrollPosition = tbody.scrollTop;
    getData();
  }
});

id.addEventListener("click", () => {
  getDataWithParams("id");
});

name.addEventListener("click", () => {
  getDataWithParams("name");
});

submitButton.addEventListener("click", (event) => {
  event.preventDefault();
  getDataWithParams("id", "ASC");
});

// MODAL

dialog.addEventListener("close", () => {
  document.body.classList.remove("no-scroll");
});

async function getData() {
  showLoading(tbody);
  isLoading = true;

  try {
    let newResponse = await getHabitats(
      searchInput.value,
      order,
      orderBy,
      count
    );

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

  newResponse.data.forEach((data) => {
    content += `<tr>
    <td>${data.id}</td>
                    <td>${data.name}</td>
                    <td class="text-oneLine">${data.description}</td>
                    <td><button name='habitatDetails' habitatId="${data.id}" class="table__button">voir</button></td>
                 </tr>`;
  });

  tbody.innerHTML = content;
  tbody.scroll(0, lastScrollPosition);

  const habitatButtons = document.querySelectorAll(
    'button[name="habitatDetails"]'
  );

  habitatButtons.forEach((button) => {
    button.addEventListener("click", (event) => {
      const habitatId = event.target.getAttribute("habitatId");
      const habitat = response.data.find((data) => data.id == habitatId);

      showDetails(habitat);
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

async function showDetails(habitat, images) {
  const habitatImages = await getHabitatImages(habitat.id);

  showDialog(habitatImages, habitat);

  const updateButton = document.querySelector("#dialog__update");
  updateButton.addEventListener("click", (event) => {
    updateHabitat(habitat);
    event.preventDefault();
  });

  const closeButton = document.querySelector(".dialog__close");
  closeButton.addEventListener("click", () => {
    dialog.close();
  });
}

function showDialog(images, habitat) {
  dialog.innerHTML = `<div class="dialog__top">
                        <h3 class="dialog__title">Habitat</h3>
                        <div class="dialog__top__buttons">
                          <button id="dialog__update" form="updateForm" class="button">modifier</button>
                          <button class="button button--red">Supprimer</button>
                          <button class='dialog__close button button--cube'>
                            <svg fill='white' xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                              <path d="M19,6.41L17.59,5L12,10.59L6.41,5L5,6.41L10.59,12L5,17.59L6.41,19L12,13.41L17.59,19L19,17.59L13.41,12L19,6.41Z" />
                            </svg>
                          </button>
                        </div>
                      </div>
                      <div id="dialog-content" class="dialog__content">
                        <form id='updateForm'>
                          <ul class="dialog__list">
                            <li class="dialog__item">
                              <label>nom</label>
                              <input required class="dialog__input" type="text" name="" id="" value="${
                                habitat.name
                              }">
                            </li>
                            <li class="dialog__item">
                              <label>description</label>
                              <div class="dialog__input-wrapper">
                                <textarea required class="dialog__textarea" name="" id="" cols="30" rows="10">${
                                  habitat.description
                                }</textarea>
                              </div>
                            </li>
                            ${setImages(images)}
                            <li class="dialog__item">
                              <label for="habitatImage">ajouter une image</label>
                              <div class="dialog__input-wrapper">
                                <form method="POST" enctype="multipart/form-data" action="upload">
                                  <input class="dialog__input" type="file" id="file" name="file">
                                  <button class="button">ajouter</button>
                                </form>
                              </div>
                            </li>
                          </ul>
                        </form> 
                      </div>`;

  dialog.showModal();
  document.body.classList.add("no-scroll");
}

function setImages(images) {
  if (images.data) {
    let content = "";
    images.data.forEach((image) => {
      content += `<li class="dialog__item">
                    <label for="habitatImage">image</label>
                    <div class="dialog__input-wrapper">
                      <img class="dialog__image" width="320px" src="${window.location.origin}/public/uploads/${image.path}">
                      <p class="dialog__input">${image.path}</p>
                      <button class="button button--cube button--red">icon</button>
                    </div>
                  </li>`;
    });
    return content;
  }
  return "";
}
