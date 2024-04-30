document.addEventListener("DOMContentLoaded", () => {
  const csrf_token = document
    .querySelector("meta[name='csrf-token']")
    .getAttribute("content");

  const buttons = document.querySelectorAll(".interactive-card__button");

  buttons.forEach((button) => {
    button.addEventListener("click", (event) => {
      event.preventDefault();
      const id = button.dataset.animalId;
      const url = button.getAttribute("href");

      fetch("/api/animals/clicks", {
        method: "POST",
        headers: {
          Accept: "application/json",
        },
        body: JSON.stringify({ id, csrf_token }),
      }).finally(() => {
        // redirect user after fetch
        window.location.href = url;
      });
    });
  });
});
