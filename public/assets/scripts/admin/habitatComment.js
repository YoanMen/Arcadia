import { FlashMessage } from "../flashMessage.js";
import { HabitatComment } from "./table/habitatCommentTable.js";
// DOM SECTION
const tbody = document.getElementById("habitatComment-tbody");
const dashboardContent = document.getElementById("dashboard-content");

// SEARCH
const searchInput = document.getElementById("habitatComment-search");
const submitButton = document.getElementById("habitatComment-submit");
const addButton = document.getElementById("habitatComment-add");
// Order Column
const name = document.getElementById("habitatComment-name");

// END DOM

const table = new HabitatComment();

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

  name.addEventListener("click", () => {
    getDataWithParams("name");
  });

  if (addButton) {
    addButton.addEventListener("click", () => {
      openNew();
    });
  }

  submitButton.addEventListener("click", (event) => {
    event.preventDefault();
    getDataWithParams("id", "ASC");
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
    let newResponse = await table.fetchData("/api/habitats-comment", "POST", {
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
  tbody.innerHTML = setTableRow(newResponse);

  tbody.scroll(0, lastScrollPosition);

  const buttons = tbody.querySelectorAll("button");

  buttons.forEach((button) => {
    // listener for habitat detail
    button.addEventListener("click", (event) => {
      const id = button.dataset.commentId;
      const data = response.data.find((data) => data.id == id);

      openDetails(data);
    });
  });
}

/**
 * open panel detail
 * @habitat data of habitat
 */
async function openDetails(data) {
  document.body.classList.add("no-scroll");

  detailPanel = document.createElement("div");
  detailPanel.classList.add("details");
  detailPanel.name = "outer-details";

  dashboardContent.appendChild(detailPanel);

  detailPanel.innerHTML = table.createDetailContent(data);
  const closeButton = document.querySelector("#details-close");

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
}

/**
 * open new panel
 */
async function openNew() {
  document.body.classList.add("no-scroll");
  detailPanel = document.createElement("div");
  detailPanel.classList.add("details");
  detailPanel.name = "outer-details";

  dashboardContent.appendChild(detailPanel);

  detailPanel.innerHTML = table.createNewContent(response.habitats);

  const create = document.getElementById("create");
  const form = document.getElementById("createForm");
  const closeButton = document.getElementById("new-close");
  const habitatSelectInput = document.getElementById("habitat-select");
  const commentInput = document.getElementById("comment");

  create.addEventListener("click", async (event) => {
    if (form.checkValidity()) {
      event.preventDefault();

      await table
        .fetchData("/api/habitats-comment/create", "POST", {
          habitat_id: habitatSelectInput.value,
          comment: commentInput.value,
        })
        .then(() => {
          new FlashMessage("success", "le commentaire à été envoyé");
          detailPanel.remove();
          document.body.classList.remove("no-scroll");
          resetData();
        })
        .catch((error) => {
          new FlashMessage(
            "error",
            `impossible d'envoyer le commentaire : ${error.message}`
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
