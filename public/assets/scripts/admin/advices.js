const advices = document.querySelectorAll(".advice-js");
const csrf_token = document.getElementById("csrf_token").value;

advices.forEach((advice) => {
  const slider = advice.querySelector("#approved");

  slider.addEventListener("click", () => {
    updateSchedule();
  });

  async function updateSchedule() {
    const id = slider.dataset.adviceId;

    // send to backend for update
    const r = await fetch("/api/advices", {
      method: "PUT",
      headers: {
        Accept: "application/json",
      },
      body: JSON.stringify({
        id: id,
        approved: slider.checked,
        csrf_token: csrf_token,
      }),
    });

    if (!r.ok) {
      location.reload();
    }
  }
});
