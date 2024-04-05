import { FlashMessage } from "../flashMessage.js";
import { ServiceTable } from "./table/serviceTable.js";

// DOM SECTION
const add = document.getElementById("service-add");
const tbody = document.getElementById("service-tbody");
const dialogConfirm = document.querySelector(".dialog--confirm");
const dashboardContent = document.getElementById("dashboard-content");
// SEARCH
const searchInput = document.getElementById("search");
const submitButton = document.getElementById("submit");

// Order Column
const id = document.getElementById("service-id");
const name = document.getElementById("service-name");

// END DOM

const table = new ServiceTable();

let response = null;
let count = 0;
let lastScrollPosition = 0;
let isLoading = false;

let detailPanel = "";

let order = "ASC";
let orderBy = "id";

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
    let newResponse = await table.fetchData("/api/services", "POST", {
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
      const id = button.dataset.serviceId;
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

  detailPanel.innerHTML = table.createNewContent();

  const create = document.getElementById("create");
  const form = document.getElementById("createForm");
  const closeButton = document.getElementById("new-close");
  const nameInput = document.getElementById("name");
  const descriptionInput = document.getElementById("description");

  create.addEventListener("click", async (event) => {
    if (form.checkValidity()) {
      event.preventDefault();
      if (table.verifyName(nameInput.value)) {
        new FlashMessage(
          "error",
          "le nom ne doit pas contenir de caractère spéciaux"
        );

        return;
      }

      await table
        .fetchData("/api/services/create", "POST", {
          name: nameInput.value,
          description: descriptionInput.value,
        })
        .then(() => {
          new FlashMessage("success", "le service à été crée");
          detailPanel.remove();
          document.body.classList.remove("no-scroll");
          resetData();
        })
        .catch((error) => {
          new FlashMessage(
            "error",
            `impossible d'ajouter le service : ${error.message}`
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
}
/**
 * open panel detail
 * @animal data of animal
 */
async function openDetails(service) {
  document.body.classList.add("no-scroll");

  detailPanel = document.createElement("div");
  detailPanel.classList.add("details");
  detailPanel.name = "outer-details";

  dashboardContent.appendChild(detailPanel);

  detailPanel.innerHTML = table.createDetailContent(service);

  const form = document.getElementById("updateForm");
  const updateButton = document.getElementById("dialog-update");
  const closeButton = document.querySelector("#details-close");
  const deleteButton = detailPanel.querySelector("#dialog-delete");
  const nameInput = document.getElementById("name");
  const descriptionInput = document.getElementById("description");

  // When click on update button
  updateButton.addEventListener("click", async (event) => {
    if (form.checkValidity()) {
      event.preventDefault();

      if (table.verifyName(nameInput.value)) {
        new FlashMessage(
          "error",
          "le nom ne doit pas contenir de caractère spéciaux"
        );

        return;
      }

      // send data to back end
      await table
        .fetchData("/api/services/update", "POST", {
          id: service.id,
          name: nameInput.value,
          description: descriptionInput.value,
        })
        .then(() => {
          new FlashMessage(
            "success",
            `le service ${service.name} à été modifié`
          );

          // close panel and reset data
          detailPanel.remove();
          document.body.classList.remove("no-scroll");
          resetData();
        })
        .catch((error) => {
          new FlashMessage(
            "error",
            `impossible de modifier le service : ${error.message}`
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

  // close detail panel and remove panel element
  closeButton.addEventListener("click", () => {
    document.body.classList.remove("no-scroll");
    detailPanel.outerHTML = "";
  });

  // delete dialog with callback for action when accept is pressed
  deleteButton.addEventListener("click", () => {
    table.showDeleteDialog(
      dialogConfirm,
      "Supprimer le service ?",
      "Voulez-vous vraiment supprimer le service ?",
      async () =>
        await table
          .fetchData("/api/services", "DELETE", { id: service.id })
          .then(() => {
            new FlashMessage("success", "le service à bien été supprimé");
            detailPanel.remove();
            document.body.classList.remove("no-scroll");
            resetData();
          })
          .catch((error) => {
            new FlashMessage(
              "error",
              `impossible de supprimer le service ${error.message}`
            );
          })
    );
  });
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
