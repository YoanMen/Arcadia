import { getDataTable, setImages, showLoading } from "./table.js";

const dialog = document.querySelector("dialog");
const table = document.getElementById("habitat-table");
const searchInput = document.getElementById("habitat-search");
const submitButton = document.getElementById("habitat-submit");
const id = document.getElementById("habitat-id");
const name = document.getElementById("habitat-name");
const tbody = document.getElementById("habitat-tbody");

let order = "ASC";
let orderBy = "id";
let response = null;
let isLoading = false;
let count = 0;
let lastScrollPosition = 0;
let content = ``;

getData();
listeners();

function listeners() {
  tbody.addEventListener("scroll", scrollListener);

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

  dialog.addEventListener("close", () => {
    document.body.classList.remove("no-scroll");
  });
}
async function getData() {
  showLoading(tbody);
  isLoading = true;

  try {
    let newResponse = await getDataTable("/api/habitats", "POST", {
      search: searchInput.value,
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
  content = setTableRow(newResponse, th);

  tbody.innerHTML = content;
  tbody.scroll(0, lastScrollPosition);

  const buttons = tbody.querySelectorAll("button");

  buttons.forEach((button) => {
    button.addEventListener("click", (event) => {
      const id = event.target.getAttribute("id");
      const habitat = response.data.find((data) => data.id == id);

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

async function showDetails(habitat) {
  const habitatImages = await getDataTable("/api/habitats/images", "POST", {
    id: habitat.id,
  });

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
                        <h3 class="dialog__title hidden--mobile">Habitat</h3>
                        <div class="dialog__top__buttons">
                          <button id="dialog__update" class="button   button--cube">                       
                          <svg fill="white" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M20.71,7.04C21.1,6.65 21.1,6 20.71,5.63L18.37,3.29C18,2.9 17.35,2.9 16.96,3.29L15.12,5.12L18.87,8.87M3,17.25V21H6.75L17.81,9.93L14.06,6.18L3,17.25Z" /></svg>
                          </button>
                          <button id="dialog__delete" class="button button--red  button--cube">                       
                             <svg height='32px' fill='white' xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M19,4H15.5L14.5,3H9.5L8.5,4H5V6H19M6,19A2,2 0 0,0 8,21H16A2,2 0 0,0 18,19V7H6V19Z" /></svg>
                          </button>
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
                                  <button class="button max-width--mobile">ajouter</button>
                                </form>
                              </div>
                            </li>
                          </ul>
                        </form> 
                      </div>`;

  if (images) {
    const imageButton = document.querySelectorAll('button[name="deleteImage"]');

    imageButton.forEach((button) => {
      button.addEventListener("click", (event) => {
        event.preventDefault();
        const imageID = event.target.getAttribute("imageID");
        const image = images.data.find((data) => data.id == imageID);
        console.log("delete");
      });
    });
  }

  dialog.showModal();
  document.body.classList.add("no-scroll");
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
                  <td class="hidden--mobile">${data.id} </td>
                  <td >${data.name} </td>
                  <td class="hidden--mobile two-line">${data.description} </td>
                  
                  <td><button id="${data.id}" class="table__button">voir</button></td>
                </tr>`;
  });

  return content;
}
