import { Table } from "./table.js";

export class AdviceTable extends Table {
  createRow(data) {
    return `<tr>
              <td>${data.pseudo}</td>
              <td>${data.advice}</td>
              <td>
              <label class="switch">
                <input class="advice-js" data-advice-id=${
                  data.id
                } type="checkbox" ${data.approved ? "checked" : ""}>
                <span class="slider round"></span>
              </label>
            </td>           
           </tr>`;
  }
}
