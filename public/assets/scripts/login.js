const connectForm = document.querySelector("#login-form");
const formBtn = document.querySelector("#connect-button");
connectForm.addEventListener("submit", () => {
  formBtn.disabled = true;
});
