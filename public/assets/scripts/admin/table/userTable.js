import { Table } from "./table.js";

export class UserTable extends Table {
  createNewContent() {
    return `<div class="details__container details__container--show">
              <div class="details__top">
                <h3 class=" hidden--mobile">Créer un utilisateur</h3>
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
                    <label for='email'>adresse mail</label>
                    <input required minlength="3" maxlength="60" class="details__input" type="text" id="email"" >
                  </  li>
                  <li class="details__item">
                    <label for='password'>mot de passe</label>
                    <input required minlength="8" maxlength="60" class="details__input" type="text" id="password"" >
                  </li>
                  <li class="details__item">
                    <label for='role'>type de compte</label>
                    <select id="role-select">
                       ${setRoleOption()}
                    </select>
                  </li>
                </ul>
              </form>
             </div>
            </div>`;
  }

  createDetailContent(data) {
    return `<div class="details__container details__container--show">
              <div class="details__top">
                <h3 class=" hidden--mobile">Détail de ${data.email}</h3>
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
                    <label for='name'>adresse email</label>
                    <input required minlength="3" maxlength="60"  class="details__input" type="text" id="email"" value="${
                      data.email
                    }">
                  </li>
                  <li class="details__item">
                    <label for='password'>mot de passe</label>
                    <input required minlength="8" maxlength="60"  class="details__input" type="text" id="password"" value="${
                      data.password
                    }">
                  </li>
                  <li class="details__item">
                    <label for='role'>type de compte</label>
                    <select id="role-select">
                     ${setRoleOption(data.role)}
                    </select>
                  </li>
                </ul>
              </form>
            </div>
           </div>`;
  }

  createRow(data) {
    return `<tr>
              <td class="hidden--mobile">${data.id} </td>
              <td >${data.email} </td>
              <td class="hidden--mobile">*****</td>
              <td class="hidden--mobile">${
                data.role == "employee" ? "employé" : "vétérinaire"
              } </td>
              <td><button data-user-id="${
                data.id
              }" class="table__button">voir</button></td>
            </tr>`;
  }
}

function setRoleOption(data = "") {
  let content = "";

  const employee = data == "employee" ? "selected" : "";
  const veterinary = data == "veterinary" ? "selected" : "";

  content += `<option ${employee} value="employee" >employé</option>
              <option ${veterinary} value="veterinary" >vétérinaire</option>`;

  return content;
}
