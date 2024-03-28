import { FlashMessage } from "../flashMessage.js";
import { Table } from "./table/table.js";

const schedules = document.querySelectorAll(".schedule-js");

schedules.forEach((schedule) => {
  const button = schedule.querySelector("button");
  const open = schedule.querySelector("#time-open");
  const close = schedule.querySelector("#time-close");
  const slider = schedule.querySelector("#close");

  if (button) {
    const id = button.dataset.scheduleId;
    button.addEventListener("click", async (event) => {
      event.preventDefault();

      // send to backend for update
      await new Table()
        .fetchData("/api/schedules", "POST", {
          id: id,
          open: open.value,
          close: close.value,
        })
        .then(() => {
          new FlashMessage("success", "l'horaire à été mis à jour");
        })
        .catch((error) => {
          new FlashMessage(
            "error",
            `impossible de mettre à jour l'horaire : ${error.message}`
          );
        });
    });
  }

  slider.addEventListener("click", function (event) {
    if (close.value != null && open.value != null) {
      if (slider.checked) {
        event.preventDefault();
      } else {
        open.value = null;
        close.value = null;
      }
    }
  });

  open.addEventListener("change", (event) => {
    if (close.value != "") {
      slider.checked = true;
    }
    if (open.value == "") {
      slider.checked = false;
    }
  });

  close.addEventListener("change", (event) => {
    if (open.value != "") {
      slider.checked = true;
    }
    if (close.value == "") {
      slider.checked = false;
    }
  });
});
