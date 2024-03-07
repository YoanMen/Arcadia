const dialog = document.querySelector("dialog");
const form = document.querySelector(".dialog__form");
const closeButton = document.querySelector(".dialog__close");
const addBtn = document.getElementById("add-testimonial");
const sendBtn = document.getElementById("send-button");

const pseudoInput = document.getElementById("pseudo");
const messageInput = document.getElementById("message");

const csrf_token = document.head.querySelector(
  'meta[name="csrf-token"]'
).content;

pseudoInput.addEventListener("input", CanSend);
messageInput.addEventListener("input", CanSend);

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

function CanSend() {
  console.log(messageInput.value.length);
  if (messageInput.value.length != 0 && pseudoInput.value.length != 0) {
    sendBtn.disabled = false;
  } else {
    sendBtn.disabled = true;
  }
}

async function sendAdvice() {
  const r = await fetch("/public/api/advice/send", {
    method: "POST",
    headers: {
      "X-CSRF-TOKEN": csrf_token,
      Accept: "application/json",
      "Content-Type": "application/json",
    },
    body: JSON.stringify({
      pseudo: pseudoInput.value,
      message: messageInput.value,
    }),
  });

  if (r.ok) {
    form.innerHTML = `<p>Votre avis à été envoyé, il sera visible après validation</p>`;
    // advice send
  }

  console.log(r);
  form.innerHTML = `<p>Erreur :</p>`;

  throw new Error("Cant get advice");
}
