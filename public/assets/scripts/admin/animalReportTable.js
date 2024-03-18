import { getAnimalsDetail } from "../fetchData.js";
import { showLoading } from "./loading.js";

let order = "DESC";
let orderBy = "date";
let response = null;

let isLoading = false;
let count = 0;
let lastScrollPosition = 0;
let content = ``;

const dialog = document.querySelector("dialog");
const dialogContent = document.querySelector("#dialog-content");
const closeButton = document.querySelector(".dialog__close");
const submitButton = document.querySelector("#animalReport-submit");
const searchInput = document.querySelector("#animalReport-search");
const dateInput = document.querySelector("#animalReport-dateTime");
const name = document.querySelector("#animalReport-name");
const race = document.querySelector("#animalReport-race");
const date = document.querySelector("#animalReport-date");
const tbody = document.querySelector("#animalReport-tbody");

getData();

tbody.addEventListener("scroll", () => {
  console.log(response.totalCount);
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

date.addEventListener("click", () => {
  getDataWithRequest("date");
});

race.addEventListener("click", () => {
  getDataWithRequest("race");
});

submitButton.addEventListener("click", (event) => {
  event.preventDefault();
  getDataWithRequest("date", "ASC");
});

function getDataWithRequest($orderBy, newOrder = null) {
  if (newOrder == null) {
    order = order == "ASC" ? "DESC" : "ASC";
  }
  orderBy = $orderBy;
  response = null;
  count = 0;
  content = "";

  lastScrollPosition = 0;
  getData();
}

async function getData() {
  showLoading(tbody);
  isLoading = true;

  try {
    let newResponse = await getAnimalsDetail(
      searchInput.value,
      dateInput.value,
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
                    <td> ${data.animalName} </td>
                    <td class="hidden--mobile"> ${data.race} </td>
                    <td class="hidden--mobile"> ${data.statut} </td>
                    <td class="hidden--mobile"> ${data.food} </td>
                    <td class="hidden--mobile"> ${data.weight}g </td>
                    <td> ${data.date} </td>
                     <td><button name="reportDetails" reportId="${data.id}" class="table__button">voir</button></td>
                  </tr>`;
    });

    tbody.innerHTML = content;
    tbody.scroll(0, lastScrollPosition);

    const reportButtons = document.querySelectorAll(
      'button[name="reportDetails"]'
    );

    reportButtons.forEach((button) => {
      button.addEventListener("click", (event) => {
        const reportId = event.target.getAttribute("reportId");
        const report = response.data.find((data) => data.id == reportId);

        show(report);
      });
    });
  } catch {
    tbody.innerHTML = `<tr class='max-height'>
                        <td>impossible de récupérer les données</td>        
                       </tr>`;
  }

  isLoading = false;
}

function show(report) {
  document.body.classList.add("no-scroll");

  dialogContent.innerHTML = `<h3 class="dialog__title">Rapport</h3>
                             <ul class="dialog__list">
                                <li class="dialog__item">
                                  <p>nom</p>
                                  <p>${report.animalName}</p>
                                </li>
                                <li class="dialog__item">
                                  <p>race</p>
                                  <p>${report.race}</p>
                                </li>
                                <li class="dialog__item">
                                  <p>de</p>
                                  <p>${report.userName}</p>
                                </li>
                                <li class="dialog__item">
                                  <p>date</p>
                                  <p>${report.date}</p>
                                </li>
                                <li class="dialog__item">
                                  <p>nourriture</p>
                                  <p>${report.food}</p>
                                </li>
                                <li class="dialog__item">
                                <p>poids</p>
                                <p>${report.weight}g</p>
                                </li>
                                <li class="dialog__item">
                                  <p>détail</p>
                                  <p>${report.details}</p>
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
