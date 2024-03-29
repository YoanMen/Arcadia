import { FlashMessage } from "../flashMessage.js";
import { HabitatTable } from "./table/habitatTable.js";

// DOM SECTION
const add = document.getElementById("habitat-add");
const tbody = document.getElementById("habitat-tbody");
const dialogConfirm = document.querySelector(".dialog--confirm");
const dashboardContent = document.getElementById("dashboard-content");
// SEARCH
const searchInput = document.getElementById("habitat-search");
const submitButton = document.getElementById("habitat-submit");

// Order Column
const id = document.getElementById("habitat-id");
const name = document.getElementById("habitat-name");

// END DOM

const table = new HabitatTable();

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
    let newResponse = await table.fetchData("/api/habitats", "POST", {
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
      const id = button.dataset.habitatId;
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
  const fileInput = document.getElementById("image-input");
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
        .create(nameInput.value, descriptionInput.value, fileInput.files[0])
        .then(() => {
          new FlashMessage("success", "l'habitat à été crée");
          detailPanel.remove();
          document.body.classList.remove("no-scroll");
          resetData();
        })
        .catch((error) => {
          new FlashMessage(
            "error",
            `impossible d'ajouter l'habitat : ${error}`
          );
        });
    }
  });

  fileInput.addEventListener("change", () => {
    if (
      !table.verifyFileType(fileInput.files[0]) ||
      !table.verifyFileSize(fileInput.files[0])
    ) {
      if (!table.verifyFileType(fileInput.files[0])) {
        new FlashMessage("error", "le type d'image n'est pas valide");
      }

      if (!table.verifyFileSize(fileInput.files[0])) {
        new FlashMessage("error", "la taille de l'image est trop volumineuse");
      }

      fileInput.value = "";
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
 * @habitat data of habitat
 */
async function openDetails(habitat) {
  document.body.classList.add("no-scroll");

  const images = await table.fetchData("/api/habitats/images", "POST", {
    id: habitat.id,
  });

  detailPanel = document.createElement("div");
  detailPanel.classList.add("details");
  detailPanel.name = "outer-details";

  dashboardContent.appendChild(detailPanel);

  detailPanel.innerHTML = table.createDetailContent(
    habitat,
    table.initImages(images)
  );

  const form = document.getElementById("updateForm");
  const updateButton = document.getElementById("dialog-update");
  const closeButton = document.querySelector("#details-close");
  const fileInput = detailPanel.querySelector("#image-input");
  const addImageButton = detailPanel.querySelector("#add-image");
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
        .fetchData("/api/habitats/update", "POST", {
          id: habitat.id,
          name: nameInput.value,
          description: descriptionInput.value,
        })
        .then(() => {
          new FlashMessage(
            "success",
            `l'habitat ${habitat.name} à été modifié`
          );

          // close panel and reset data
          detailPanel.remove();
          document.body.classList.remove("no-scroll");
          resetData();
        })
        .catch((error) => {
          new FlashMessage(
            "error",
            `impossible de modifier l'habitat : ${error}`
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

  // when file is selected, update button add
  fileInput.addEventListener("change", (event) => {
    addImageButton.disabled = false;
  });

  // delete dialog with callback for action when accept is pressed
  deleteButton.addEventListener("click", () => {
    table.showDeleteDialog(
      dialogConfirm,
      "Supprimer l'habitat ?",
      "Voulez-vous vraiment supprimer l'habitat ?",
      async () =>
        await table
          .fetchData("/api/habitats", "DELETE", { id: habitat.id })
          .then(() => {
            new FlashMessage("success", "l'habitat à bien été supprimé");
            detailPanel.remove();
            document.body.classList.remove("no-scroll");
            resetData();
          })
          .catch((error) => {
            new FlashMessage(
              "error",
              `impossible de supprimer l'habitat ${error}`
            );
          })
    );
  });

  // for uploading more image for habitat
  detailPanel
    .querySelector("#add-image")
    .addEventListener("click", async (event) => {
      try {
        event.preventDefault();

        const data = await table.uploadImage(
          "api/habitats/uploadImage",
          habitat.id,
          fileInput.files[0]
        );

        new FlashMessage("success", `image ajoutée pour ${habitat.name}`);

        // reset file input
        fileInput.value = "";
        addImageButton.disabled = true;

        const imageList = document.getElementById("images-list");

        // insert new image to list and listening delete buttons

        imageList.insertAdjacentHTML("beforeend", table.insertImage(data));
        listeningButtonsDeleteImage();
      } catch (error) {
        new FlashMessage(
          "error",
          `impossible d'ajouter pour ${habitat.name} : ${error}`
        );
      }
    });

  // when habitat has image listening image delete button
  if (images) {
    listeningButtonsDeleteImage();
  }
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
 * listening delete button for images
 */
function listeningButtonsDeleteImage() {
  const imageButton = document.querySelectorAll('button[name="deleteImage"]');

  imageButton.forEach((button) => {
    button.addEventListener("click", (event) => {
      event.preventDefault();
      const imageID = button.dataset.imageId;

      table.showDeleteDialog(
        dialogConfirm,
        "Supprimer image ?",
        "Voulez-vous supprimer cette image ?",
        async () =>
          await table
            .fetchData("/api/habitats/images", "DELETE", { id: imageID })
            .then(() => {
              const imageLi = button.closest("li");
              imageLi.remove();
              new FlashMessage("success", "L'image à bien été supprimé");
            })
            .catch((error) => {
              new FlashMessage(
                "error",
                `Impossible de supprimer l'image : ${error}`
              );
            })
      );
    });
  });
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
