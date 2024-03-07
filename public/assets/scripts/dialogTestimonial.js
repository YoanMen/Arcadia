const dialog = document.querySelector("dialog");
const form = document.querySelector(".dialog__form");
const closeButton = document.querySelector(".dialog__close");
const addBtn = document.getElementById("add-testimonial");
const sendBtn = document.getElementById("send-button");
const csrf_token = document.head.querySelector(
  'meta[name="csrf-token"]'
).content;

addBtn.addEventListener("click", () => {
  dialog.showModal();
  document.body.classList.add("no-scroll");
});

closeButton.addEventListener("click", () => {
  dialog.close();
});

dialog.addEventListener("open", () => {
  document.body.classList.add("no-scroll");
});
dialog.addEventListener("close", () => {
  document.body.classList.remove("no-scroll");
});

dialog.addEventListener("submit", (e) => {
  e.preventDefault();

  sendAdvice();
});

async function sendAdvice() {
  const pseudoInput = document.getElementById("pseudo").value;
  const messageInput = document.getElementById("message").value;

  const r = await fetch("/public/api/advice/send", {
    method: "POST",
    headers: {
      "X-CSRF-TOKEN": csrf_token,
      Accept: "application/json",
      "Content-Type": "application/json",
    },
    body: JSON.stringify({
      pseudo: pseudoInput,
      message: messageInput,
    }),
  });

  if (r.ok) {
    return r.json();
    // advice send
  }
  throw new Error("Cant get advice");
}
