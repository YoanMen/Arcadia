import { Table } from "./table.js";

export class HabitatComment extends Table {
  createRow(data) {
    return `<tr>
              <td >${data.habitatName} </td>
              <td class="hidden--mobile two-line" >${data.comment}</td>
              <td><button data-comment-id="${data.id}" class="table__button">voir</button></td>
            </tr>`;
  }

  createDetailContent(data) {
    return `<div class="details__container details__container--show">
              <div class="details__top">
                <h3 class=" hidden--mobile">Commentaire sur ${data.habitatName}</h3>
                <div class="details__top__buttons">
                  <button id="details-close" class='button button--cube'>
                    <svg fill='white' xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                      <path d="M19,6.41L17.59,5L12,10.59L6.41,5L5,6.41L10.59,12L5,17.59L6.41,19L12,13.41L17.59,19L19,17.59L13.41,12L19,6.41Z" />
                    </svg>
                  </button>
                </div>
              </div>
              <div id="details-content" class="details__content">
                <ul>
                  <li class="details__item">
                    <span>habitat</span>
                    <p  class="details__input" <p>${data.habitatName}</p>
                  </li>
                  <li class="details__item">
                    <span>de</span>
                    <p  class="details__input" <p>${data.userName}</p>
                  </li>
                  <li class="details__item">
                    <span>description</span>
                    <p  class="details__input" <p>${data.comment}</p>
                  </li>
                </ul>
              </div>
            </div>`;
  }

  createNewContent(habitats) {
    return `<div class="details__container details__container--show">
              <div class="details__top">
                <h3 class=" hidden--mobile">Créer un nouveau commentaire</h3>
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
                    <label for='habitat-select'>habitat</label>
                    <select required id="habitat-select">
                      ${setHabitatOption(habitats)}
                    </select>
                  </li>
                  <li class="details__item">
                    <label for='comment'>commentaire</label>
                    <textarea required minlength="3" class="details__textarea" id="comment" cols="30" rows="10"></textarea>
                  </li>
                </ul>
              </form>
             </div>
            </div>`;
  }
}

function setHabitatOption(habitats) {
  let content = "";

  habitats.forEach((habitat) => {
    content += `<option value="${habitat.id}">${habitat.name}</option>`;
  });

  return content;
}
