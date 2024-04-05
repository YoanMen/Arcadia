import { Table } from "./table.js";
export class FoodAnimalTable extends Table {
  createNewContent(habitats) {
    return `<div class="details__container details__container--show">
              <div class="details__top">
                <h3 class=" hidden--mobile">Créer</h3>
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
                    <select id="habitat-select">
                    <option value='0'>-- sélectionner un habitat</option>
                      ${setHabitatOption(habitats)}
                    </select>
                  </li>
                  <li class="details__item">
                    <label for='animal-select'>animal</label>
                    <select disabled required id="animal-select">
                    </select>
                  </li>
                  <li class="details__item">
                    <label for='food'>nourriture</label>
                    <input required minlength="3" maxlength="20" class="details__input" type="text" id="food"" >
                  </li>
                  <li class="details__item">
                    <label for='quantity'>quantité en gramme</label>
                    <input required  class="details__input" type="number" id="quantity"" >
                  </li>
                  <li class="details__item">
                    <label for='quantity'>date</label>
                    <input required class="details__input" type="date" id="date"" >
                  </li>
                  <li class="details__item">
                    <label for='quantity'>heure</label>
                    <input required class="details__input" type="time" id="time"" >
                  </li>
                </ul>
              </form>
             </div>
            </div>`;
  }

  createDetailContent(data) {
    return `<div class="details__container details__container--show">
              <div class="details__top">
                <h3 class=" hidden--mobile">Détail nourriture </h3>
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
                    <label>nom</label>
                    <p  class="details__input" <p>${data.name}</p>
                  </li>
                  <li class="details__item">
                    <span>habitat</span>
                    <p  class="details__input" <p>${data.habitat}</p>
                  </li>
                  <li class="details__item">
                  <label>nourriture</label>
                  <p  class="details__input" <p>${data.food}</p>
                </li>
                <li class="details__item">
                  <label>quantité</label>
                  <p  class="details__input" <p>${data.quantity}g</p>
                </li>
                <li class="details__item">
                  <label>date</label>
                  <p  class="details__input" <p>${data.date}</p>
                </li>
                <li class="details__item">
                  <label>heure</label>
                  <p  class="details__input" <p>${data.time}</p>
                </li>
               </ul>
             </div>
            </div>`;
  }

  createRow(data) {
    return `<tr>
              <td class="hidden--mobile">${data.name} </td>
              <td >${data.habitat} </td>
              <td class="hidden--mobile">${data.food} </td>
              <td class="hidden--mobile">${data.quantity}g </td>
              <td >${data.date} </td>
              <td class="hidden--mobile">${data.time} </td>
              <td><button data-food-id="${data.id}" class="table__button">voir</button></td>
            </tr>`;
  }
}

function setHabitatOption(habitats) {
  let content = "";

  habitats.forEach((habitat) => {
    content += `<option value="${habitat.id}">${habitat.name}</option>`;
  });

  return content;
}
