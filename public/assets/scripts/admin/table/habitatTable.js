import { Table } from "./table.js";

export class HabitatTable extends Table {
  createNewContent() {
    return `<div class="details__container details__container--show">
              <div class="details__top">
                <h3 class=" hidden--mobile">Créer un nouveau habitat</h3>
                <div class="details__top__buttons">
                  <button form='createForm' id="create" class="button button--cube">
                      <svg fill="white" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                        <path d="M19,13H13V19H11V13H5V11H11V5H13V11H19V13Z" />
                      </svg>
                  </button>
                  <button id="new-close" class='button button--cube'>
                    <svg fill='white' xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                      <path d="M19,6.41L17.59,5L12,10.59L6.41,5L5,6.41L10.59,12L5,17.59L6.41,19L12,13.41L17.59,19L19,17.59L13.41,12L19,6.41Z" />
                    </svg>
                  </button>
                </div>
              </div>
              <div id="details-content" class="details__content">
              <form id='createForm'>
                <ul>
                  <li class="details__item">
                    <label for='name'>nom</label>
                    <input required minlength="3" maxlength="60" class="details__input" type="text" id="name"" >
                  </li>
                  <li class="details__item">
                    <label for='description'>description</label>
                    <textarea required minlength="3" class="details__textarea" id="description" cols="30" rows="10"></textarea>
                  </li>
                  <li class="details__item">
                    <label for="habitatImage">image</label>
                    <input required id="image-input" class="details__input" type="file" id="file" name="file"  accept="image/png, image/jpeg, image/webp">
                  </li>
                </ul>
              </form>
             </div>
            </div>`;
  }

  createDetailContent(data, initImages) {
    return `<div class="details__container details__container--show">
              <div class="details__top">
                <h3 class=" hidden--mobile">Détail de ${data.name}</h3>
                <div class="details__top__buttons">
                  <button form="updateForm" id="dialog-update" class="button button--cube">
                    <svg fill="white" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                        <path d="M20.71,7.04C21.1,6.65 21.1,6 20.71,5.63L18.37,3.29C18,2.9 17.35,2.9 16.96,3.29L15.12,5.12L18.87,8.87M3,17.25V21H6.75L17.81,9.93L14.06,6.18L3,17.25Z" />
                    </svg>
                  </button>
                  <button id="dialog-delete" class="button button--red  button--cube">
                    <svg height='32px' fill='white' xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                      <path d="M19,4H15.5L14.5,3H9.5L8.5,4H5V6H19M6,19A2,2 0 0,0 8,21H16A2,2 0 0,0 18,19V7H6V19Z" />
                    </svg>
                  </button>
                  <button id="details-close" class='button button--cube'>
                    <svg fill='white' xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                      <path d="M19,6.41L17.59,5L12,10.59L6.41,5L5,6.41L10.59,12L5,17.59L6.41,19L12,13.41L17.59,19L19,17.59L13.41,12L19,6.41Z" />
                    </svg>
                  </button>
                </div>
              </div>
              <div id="details-content" class="details__content">
              <form id='updateForm'>
                <ul>
                  <li class="details__item">
                    <label for='name'>nom</label>
                    <input required class="details__input" type="text" id="name"" value="${data.name}">
                  </li>
                  <li class="details__item">
                    <label for='description'>description</label>
                    <textarea required class="details__textarea" id="description" cols="30" rows="10">${data.description}</textarea>
                  </li>
                  <li class="details__item">
                    <label for="habitatImage">ajouter une image</label>
                    <div class="details__input-wrapper">
                      <form id="form-image" method="POST" enctype="multipart/form-data">
                        <input id="image-input" class="details__input" type="file" id="file" id="file" accept="image/png, image/jpeg, image/webp">
                        <button id="add-image" disabled form="form-image" class="button max-width--mobile">ajouter</button>
                      </form>
                    </div>
                  </li>
                  <ul class="" id="images-list">
                    ${initImages}
                  </ul>
                </ul>
              </form>
              </div>
            </div>`;
  }

  createRow(data) {
    return `<tr>
              <td class="hidden--mobile">${data.id} </td>
              <td >${data.name} </td>
              <td class="hidden--mobile two-line">${data.description} </td>
              <td><button data-habitat-id="${data.id}" class="table__button">voir</button></td>
            </tr>`;
  }

  async create(name, description, file) {
    try {
      const formData = new FormData();
      formData.append("name", name);
      formData.append("description", description);
      formData.append("file", file);

      const r = await fetch("/public/api/habitats/create", {
        method: "POST",
        headers: {
          "X-CSRF-TOKEN": this.csrf_token,
        },
        body: formData,
      });

      const data = await r.json();

      if (r.ok) {
        return data;
      } else {
        throw new Error(data.error || "Erreur inattendue du serveur");
      }
    } catch (error) {
      throw new Error(error.message);
    }
  }
}
