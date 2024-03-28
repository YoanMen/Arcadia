import { AnimalReport } from "./table/animalReportTable.js";

// DOM SECTION
const tbody = document.getElementById("animalReport-tbody");
const dashboardContent = document.getElementById("dashboard-content");

// SEARCH
const searchInput = document.getElementById("animalReport-search");
const submitButton = document.getElementById("animalReport-submit");
const dateInput = document.getElementById("animalReport-dateTime");

// Order Column
const name = document.getElementById("animalReport-name");
const race = document.getElementById("animalReport-race");
const date = document.getElementById("animalReport-date");

// END DOM

const table = new AnimalReport();

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

  race.addEventListener("click", () => {
    getDataWithParams("race");
  });

  date.addEventListener("click", () => {
    getDataWithParams("date");
  });

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
    let newResponse = await table.fetchData("/api/animals/report", "POST", {
      search: searchInput.value,
      date: dateInput.value,
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
  // concat date if have old data
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

  // keep scroll position in table
  tbody.scroll(0, lastScrollPosition);

  // listeners event details buttons
  const buttons = tbody.querySelectorAll("button");
  buttons.forEach((button) => {
    // listener for habitat detail
    button.addEventListener("click", (event) => {
      const id = button.dataset.reportId;
      const data = response.data.find((data) => data.id == id);

      openDetails(data);
    });
  });
}

/**
 * open panel detail
 * @params data values
 */
async function openDetails(data) {
  document.body.classList.add("no-scroll");

  // create dialog
  detailPanel = document.createElement("div");
  detailPanel.classList.add("details");

  dashboardContent.appendChild(detailPanel);

  detailPanel.innerHTML = table.createDetailContent(data);

  // close detail panel and remove panel element
  const closeButton = document.querySelector("#details-close");
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
