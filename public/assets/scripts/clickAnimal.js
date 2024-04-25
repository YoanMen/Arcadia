const csrf_token = document.head.querySelector(
  'meta[name="csrf-token"]'
).content;
const buttons = document.querySelectorAll(".interactive-card__button");

buttons.forEach((button) => {
  button.addEventListener("click", async (event) => {
    event.preventDefault();
    const id = button.dataset.animalId;
    const url = button.getAttribute("href");

    await fetch(`/api/animals/${id}/click`, {
      method: "GET",
      headers: {
        Accept: "application/json",
        "X-CSRF-TOKEN": csrf_token,
      },
    }).finally(() => {
      // redirect user after fetch
      window.location.href = url;
    });
  });
});
