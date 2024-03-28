import { FlashMessage } from "../flashMessage.js";
import { UserTable } from "./table/userTable.js";

// DOM SECTION
const add = document.getElementById("user-add");
const tbody = document.getElementById("user-tbody");
const dialogConfirm = document.querySelector(".dialog--confirm");
const dashboardContent = document.getElementById("dashboard-content");
// SEARCH
const searchInput = document.getElementById("search");
const submitButton = document.getElementById("submit");

// Order Column
const id = document.getElementById("user-id");
const email = document.getElementById("user-email");
const role = document.getElementById("user-role");

// END DOM

const table = new UserTable();

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

  email.addEventListener("click", () => {
    getDataWithParams("email");
  });

  role.addEventListener("click", () => {
    getDataWithParams("role");
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
    let newResponse = await table.fetchData("/api/users", "POST", {
      search: searchInput.value,
      order: order,
      orderBy: orderBy,
      count: count,
    });

    updateContent(newResponse);
  } catch (error) {
    tbody.innerHTML = `<tr class='max-height'>
                        <td>${error}</td>        
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
    // listener for  detail
    button.addEventListener("click", (event) => {
      const id = button.dataset.userId;
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

  dashboardContent.appendChild(detailPanel);

  detailPanel.innerHTML = table.createNewContent();

  const create = document.getElementById("create");
  const form = document.getElementById("createForm");
  const closeButton = document.getElementById("new-close");
  const emailInput = document.getElementById("email");
  const passwordInput = document.getElementById("password");
  const roleInput = document.getElementById("role-select");

  create.addEventListener("click", async (event) => {
    if (form.checkValidity()) {
      event.preventDefault();

      await table
        .fetchData("/api/users/create", "POST", {
          email: emailInput.value,
          password: passwordInput.value,
          role: roleInput.value,
        })
        .then(() => {
          new FlashMessage("success", "l'utilisateur à été crée");
          detailPanel.remove();
          document.body.classList.remove("no-scroll");
          resetData();
        })
        .catch((error) => {
          new FlashMessage(
            "error",
            `impossible d'ajouter l'utilisateur : ${error}`
          );
        });
    }
  });

  closeButton.addEventListener("click", () => {
    document.body.classList.remove("no-scroll");
    detailPanel.outerHTML = "";
  });
}
/**
 * open panel detail
 * @params user data
 */
async function openDetails(user) {
  document.body.classList.add("no-scroll");

  detailPanel = document.createElement("div");
  detailPanel.classList.add("details");

  dashboardContent.appendChild(detailPanel);

  detailPanel.innerHTML = table.createDetailContent(user);

  const form = document.getElementById("updateForm");
  const updateButton = document.getElementById("dialog-update");
  const closeButton = document.querySelector("#details-close");
  const deleteButton = detailPanel.querySelector("#dialog-delete");
  const emailInput = document.getElementById("email");
  const passwordInput = document.getElementById("password");
  const roleInput = document.getElementById("role-select");

  // When click on update button
  updateButton.addEventListener("click", async (event) => {
    if (form.checkValidity()) {
      event.preventDefault();

      // send data to back end
      await table
        .fetchData("/api/users/update", "POST", {
          id: user.id,
          email: emailInput.value,
          password: passwordInput.value,
          role: roleInput.value,
        })
        .then(() => {
          new FlashMessage(
            "success",
            `l'utilisateur ${user.email} à été modifié`
          );

          // close panel and reset data
          detailPanel.remove();
          document.body.classList.remove("no-scroll");
          resetData();
        })
        .catch((error) => {
          new FlashMessage(
            "error",
            `impossible de modifier l'utilisateur : ${error}`
          );
        });
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
      "Supprimer l'utilisateur ?",
      "Voulez-vous vraiment supprimer l'utilisateur ?",
      async () =>
        await table
          .fetchData("/api/users", "DELETE", { id: user.id })
          .then(() => {
            new FlashMessage("success", "l'utilisateur à bien été supprimé");
            detailPanel.remove();
            document.body.classList.remove("no-scroll");
            resetData();
          })
          .catch((error) => {
            new FlashMessage(
              "error",
              `impossible de supprimer l'utilisateur ${error}`
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
