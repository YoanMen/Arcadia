import { getAnimalsDetail } from "/public/assets/scripts/admin/fetchData.js";

const dialog = document.querySelector("dialog");
const dialogContent = document.querySelector("#dialog-content");
const closeButton = document.querySelector(".dialog__close");
const submitButton = document.querySelector("#animalReport-submit");
const searchInput = document.querySelector("#animalReport-search");

const name = document.querySelector("#animalReport-name");
const tbody = document.querySelector("#animalReport-tbody");
let reports = null;

const loading = ` <tr class="loading max-height">
                    <td>
                      <svg height='32px' xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                        <path d="M12,4V2A10,10 0 0,0 2,12H4A8,8 0 0,1 12,4Z" />
                      </svg>
                    </td>
                  </tr>`;

async function getData(order = "ASC") {
  try {
    reports = await getAnimalsDetail();
    let content = ``;

    reports.data.forEach((report) => {
      content += `<tr>
                                        <td> </td>
                                         <td><button name='habitatDetails' reportId="${report.id}" class="table__button">voir</button></td>
                                     </tr>`;
    });

    tbody.innerHTML = content;

    const reportButtons = document.querySelectorAll(
      'button[name="reportDetails"]'
    );

    reportButtons.forEach((button) => {
      button.addEventListener("click", (event) => {
        const reportId = event.target.getAttribute("habitatId");

        const report = reports.data.find((report) => reportId == id);

        showReportDetails(report);
      });
    });
  } catch {
    tbody.innerHTML = ` <tr class='max-height'>
                                            <td> Aucun résultat</td>        
                                          </tr>`;
  }
}
