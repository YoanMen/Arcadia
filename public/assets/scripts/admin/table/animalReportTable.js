import { Table } from "./table.js";

export class AnimalReport extends Table {
  createRow(data) {
    return `<tr>
              <td >${data.animalName} </td>
              <td class="hidden--mobile" >${data.race} </td>
              <td class="hidden--mobile">${data.statut} </td>
              <td class="hidden--mobile">${data.food} </td>
              <td class="hidden--mobile">${data.weight}g </td>
              <td >${data.date} </td>
              <td><button data-report-id="${data.id}" class="table__button">voir</button></td>
            </tr>`;
  }

  createDetailContent(data) {
    return `<div class="details__container details__container--show">
              <div class="details__top">
                <h3 class=" hidden--mobile">Rapport sur ${data.animalName}</h3>
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
                    <span>de</span>
                    <p required class="details__input" <p>${data.userName}</p>
                  </li>
                  <li class="details__item">
                    <span >nom</span>
                    <p required class="details__input" <p>${data.animalName}</p>
                  </li>
                  <li class="details__item">
                    <span>race</span>
                    <p required class="details__input" <p>${data.race}</p>
                  </li>
                  <li class="details__item">
                    <span>statut</span>
                    <p required class="details__input" <p>${data.statut}</p>
                  </li>
                  <li class="details__item">
                    <span>nourriture recommandé</span>
                    <p  class="details__input" <p>${data.food}</p>
                  </li>
                  <li class="details__item">
                    <span>poids de la nourriture</span>
                    <p  class="details__input" <p>${data.weight}g</p>
                  </li>
                  <li class="details__item">
                    <span>date</span>
                    <p  class="details__input" <p>${data.date}</p>
                  </li>
                  <li class="details__item">
                    <span>commentaire</span>
                    <p  class="details__input" <p>${data.details}</p>
                  </li>
                </ul>
              </div>
            </div>`;
  }
}
