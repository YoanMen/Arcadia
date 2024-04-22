const form = document.getElementById("add");
const addButton = document.getElementById("add-button");

form.addEventListener("submit", () => {
  if (form.checkValidity()) {
    addButton.disabled = true;
  }
});
