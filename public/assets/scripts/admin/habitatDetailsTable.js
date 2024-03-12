import {
  getHabitatsComment,
  getHabitatsCommentByName,
} from "/public/assets/scripts/admin/fetchData.js";

const dialog = document.querySelector("dialog");
const dialogContent = document.querySelector("#dialog-content");
const closeButton = document.querySelector(".dialog__close");

let habitats = null;

const loading = ` <tr class="loading max-height">
                    <td>
                      <svg height='32px' xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                        <path d="M12,4V2A10,10 0 0,0 2,12H4A8,8 0 0,1 12,4Z" />
                      </svg>
                    </td>
                  </tr>`;

const searchInput = document.querySelector("#habitatDetails-search");
const submitButton = document.querySelector("#habitatDetails-submit");

const name = document.querySelector("#habitatDetails-name");
const tbody = document.querySelector("#habitatDetails-tbody");

let DESCOrder = false;

name.addEventListener("click", () => {
  tbody.innerHTML = loading;

  if (!DESCOrder) {
    getData("DESC");
    DESCOrder = true;
  } else {
    getData();
    DESCOrder = false;
  }
});

submitButton.addEventListener("click", (event) => {
  event.preventDefault();
  getDataByName(searchInput.value);
});

getData();

async function getDataByName(string) {
  try {
    habitats = await getHabitatsCommentByName(string);

    let content = ``;

    habitats.data.forEach((habitat) => {
      content += `<tr>
                      <td> ${habitat.habitatName} </td>
                       <td><button name='habitatDetails' habitatId="${habitat.habitatID}" class="table__button">voir</button></td>
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
          (habitat) => habitat.habitatID == habitatId
        );

        showHabitatDetails(habitat);
      });
    });
  } catch {
    tbody.innerHTML = ` <tr class='max-height'>
                          <td> Aucun résultat</td>        
                        </tr>`;
  }
}

async function getData(order = "ASC") {
  try {
    habitats = await getHabitatsComment(order);
    let content = ``;

    habitats.data.forEach((habitat) => {
      content += `<tr>
                      <td> ${habitat.habitatName} </td>
                       <td><button name='habitatDetails' habitatId="${habitat.habitatID}" class="table__button">voir</button></td>
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
          (habitat) => habitat.habitatID == habitatId
        );

        showHabitatDetails(habitat);
      });
    });
  } catch {
    tbody.innerHTML = ` <tr class='max-height'>
                          <td> Aucun résultat</td>        
                        </tr>`;
  }
}

function showHabitatDetails(habitat) {
  document.body.classList.add("no-scroll");
  dialogContent.innerHTML = `<h4>${habitat.habitatName}</h4>
                             <p class="dialog__creator">de ${habitat.userName}</p>
                             <p class="dialog__comment">de ${habitat.comment}</p>`;

  dialog.showModal();
}

closeButton.addEventListener("click", () => {
  dialog.close();
});

dialog.addEventListener("close", () => {
  document.body.classList.remove("no-scroll");
});
