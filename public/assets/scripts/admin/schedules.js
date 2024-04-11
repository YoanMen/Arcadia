const schedules = document.querySelectorAll(".schedule-js");
const csrf_token = document.getElementById("csrf_token").value;

schedules.forEach((schedule) => {
  const open = schedule.querySelector("#time-open");
  const close = schedule.querySelector("#time-close");
  const slider = schedule.querySelector("#close");

  slider.addEventListener("click", (event) => {
    if (close.value != null && open.value != null) {
      if (slider.checked) {
        event.preventDefault();
      } else {
        open.value = null;
        close.value = null;
        updateSchedule();
      }
    }
  });

  open.addEventListener("change", (event) => {
    if (close.value != "") {
      slider.checked = true;
      updateSchedule();
    }
    if (open.value == "") {
      slider.checked = false;
    }
  });

  close.addEventListener("change", (event) => {
    if (open.value != "") {
      slider.checked = true;
      updateSchedule();
    }
    if (close.value == "") {
      slider.checked = false;
    }
  });

  async function updateSchedule() {
    const id = slider.dataset.scheduleId;

    // send to backend for update
    const r = await fetch("/public/api/schedules", {
      method: "PUT",
      headers: {
        Accept: "application/json",
      },
      body: JSON.stringify({
        id: id,
        open: open.value,
        close: close.value,
        csrf_token: csrf_token,
      }),
    });

    if (!r.ok) {
      location.reload();
    }
  }
});
