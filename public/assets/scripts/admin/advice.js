import { FlashMessage } from "../flashMessage.js";
import { AdviceTable } from "./table/adviceTable.js";
// DOM SECTION
const tbody = document.getElementById("advice-tbody");
// Order Column
const approved = document.getElementById("approved");

// END DOM

const table = new AdviceTable();

let response = null;
let count = 0;
let lastScrollPosition = 0;
let isLoading = false;
let order = "ASC";
let orderBy = "approved";

getData();
listeners();

function listeners() {
  tbody.addEventListener("scroll", scrollListener);

  approved.addEventListener("click", () => {
    getDataWithParams("name");
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
    let newResponse = await table.fetchData("/api/advices", "POST", {
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

  const buttons = tbody.querySelectorAll(".advice-js");

  buttons.forEach((button) => {
    button.addEventListener("click", async (event) => {
      const id = button.getAttribute("data-advice-id");
      console.log(id);

      await table
        .fetchData("/api/advices/update", "POST", {
          id: id,
        })
        .then(() => {
          new FlashMessage("success", "l'avis à été mis à jour");
        })
        .catch((error) => {
          new FlashMessage(
            "error",
            `impossible de mettre à jour l'avis : ${error.message}`
          );
        });
    });
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
