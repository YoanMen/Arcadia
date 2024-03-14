import { getHabitatsComment } from "/public/assets/scripts/admin/fetchData.js";

let order = "DESC";
let orderBy = "id";
let habitats = null;

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
const searchInput = document.querySelector("#habitatDetails-search");
const submitButton = document.querySelector("#habitatDetails-submit");
const name = document.querySelector("#habitatDetails-name");
const tbody = document.querySelector("#habitatDetails-tbody");

getData();

name.addEventListener("click", () => {
  order = order == "ASC" ? "DESC" : "ASC";
  orderBy = "name";

  getData();
});

submitButton.addEventListener("click", (event) => {
  event.preventDefault();
  order = "ASC";
  orderBy = "id";
  getData();
});

async function getData() {
  try {
    tbody.innerHTML = loading;

    habitats = await getHabitatsComment(searchInput.value, order, orderBy);
    let content = ``;

    habitats.data.forEach((habitat) => {
      content += `<tr>
                      <td> ${habitat.habitatName} </td>
                       <td><button name='habitatDetails' habitatId="${habitat.id}" class="table__button">voir</button></td>
                   </tr>`;
    });

    tbody.innerHTML = content;

    const habitatButtons = document.querySelectorAll(
      'button[name="habitatDetails"]'
    );

    habitatButtons.forEach((button) => {
      button.addEventListener("click", (event) => {
        const habitatId = event.target.getAttribute("habitatId");
        const habitat = habitats.data.find(
          (habitat) => habitat.id == habitatId
        );

        show(habitat);
      });
    });
  } catch {
    tbody.innerHTML = `<tr class='max-height'>
                        <td> Aucun résultat</td>        
                       </tr>`;
  }
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
