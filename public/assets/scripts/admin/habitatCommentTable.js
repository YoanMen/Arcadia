import { getHabitatsComment } from "../fetchData.js";
import { showLoading } from "./loading.js";

let order = "DESC";
let orderBy = "id";
let response = null;

let isLoading = false;
let count = 0;
let lastScrollPosition = 0;
let content = ``;

const dialog = document.querySelector("dialog");
const dialogContent = document.querySelector("#dialog-content");
const closeButton = document.querySelector(".dialog__close");
const searchInput = document.querySelector("#habitatDetails-search");
const submitButton = document.querySelector("#habitatDetails-submit");
const name = document.querySelector("#habitatDetails-name");
const tbody = document.querySelector("#habitatDetails-tbody");

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

name.addEventListener("click", () => {
  getDataWithRequest("name");
});

submitButton.addEventListener("click", (event) => {
  event.preventDefault();
  getDataWithRequest("id", "ASC");
});

function getDataWithRequest($orderBy, newOrder = null) {
  if (newOrder == null) {
    order = order == "ASC" ? "DESC" : "ASC";
  }
  orderBy = $orderBy;

  // reset value
  count = 0;
  content = "";
  response = null;
  lastScrollPosition = 0;
  getData();
}

async function getData() {
  showLoading(tbody);
  isLoading = true;

  try {
    let newResponse = await getHabitatsComment(
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

    if (response != null) {
      const oldResponse = response;
      response.data = oldResponse.data.concat(newResponse.data);
    } else {
      response = newResponse;
    }

    count += newResponse.data.length;

    newResponse.data.forEach((data) => {
      content += `<tr>
                      <td>${data.habitatName}</td>
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

        show(habitat);
      });
    });
  } catch {
    tbody.innerHTML = `<tr class='max-height'>
                        <td>impossible de récupérer les données</td>        
                       </tr>`;
  }

  isLoading = false;
}

function show(habitat) {
  document.body.classList.add("no-scroll");

  dialogContent.innerHTML = `<h3 class="dialog__title">Détail</h3>
                             <ul class="dialog__list">
                              <li class="dialog__item">
                                <p>habitat</p>
                                <p>${habitat.habitatName}</p>
                              </li>
                              <li class="dialog__item">
                                 <p>de</p>
                                 <p>${habitat.userName}</p>
                               </li>
                               <li class="dialog__item">
                                 <p>commentaire</p>
                                 <p>${habitat.comment}</p>
                               </li>
                             </ul>`;
  dialog.showModal();
}

closeButton.addEventListener("click", () => {
  dialog.close();
});

dialog.addEventListener("close", () => {
  document.body.classList.remove("no-scroll");
});
