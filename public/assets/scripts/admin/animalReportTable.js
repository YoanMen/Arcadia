import { getAnimalsDetail } from "/public/assets/scripts/admin/fetchData.js";

let order = "DESC";
let orderBy = "id";
let reports = null;

const loading = `<tr class="loading max-height">
                    <td>
                      <svg height='32px' xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                        <path d="M12,4V2A10,10 0 0,0 2,12H4A8,8 0 0,1 12,4Z" />
                      </svg>
                    </td>
                  </tr>`;

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

name.addEventListener("click", () => {
  order = order == "ASC" ? "DESC" : "ASC";
  orderBy = "name";

  getData();
});

date.addEventListener("click", () => {
  order = order == "ASC" ? "DESC" : "ASC";
  orderBy = "date";

  getData();
});

race.addEventListener("click", () => {
  order = order == "ASC" ? "DESC" : "ASC";
  orderBy = "race";

  getData();
});

submitButton.addEventListener("click", (event) => {
  event.preventDefault();
  console.log(dateInput.value);
  order = "DESC";
  orderBy = "id";
  getData();
});

async function getData() {
  try {
    tbody.innerHTML = loading;

    reports = await getAnimalsDetail(
      searchInput.value,
      dateInput.value,
      order,
      orderBy
    );
    let content = ``;

    reports.data.forEach((report) => {
      content += `<tr>
                    <td> ${report.animalName} </td>
                    <td class="hidden--mobile"> ${report.race} </td>
                    <td class="hidden--mobile"> ${report.statut} </td>
                    <td class="hidden--mobile"> ${report.food} </td>
                    <td class="hidden--mobile"> ${report.weight}g </td>
                    <td> ${report.date} </td>
                     <td><button name="reportDetails" reportId="${report.id}" class="table__button">voir</button></td>
                  </tr>`;
    });

    tbody.innerHTML = content;

    const reportButtons = document.querySelectorAll(
      'button[name="reportDetails"]'
    );

    reportButtons.forEach((button) => {
      button.addEventListener("click", (event) => {
        const reportId = event.target.getAttribute("reportId");
        const report = reports.data.find((report) => report.id == reportId);

        show(report);
      });
    });
  } catch {
    tbody.innerHTML = `<tr class='max-height'>
                        <td> Aucun résultat</td>        
                       </tr>`;
  }
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
