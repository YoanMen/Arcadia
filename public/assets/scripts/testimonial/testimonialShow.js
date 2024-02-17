const leftButton = document.getElementById("left-btn");
const rightButton = document.getElementById("right-btn");
const adviceCard = document.getElementById("testimonial-card");
const text = document.getElementById("testimonial-text");
const pseudo = document.getElementById("testimonial-pseudo");

let currentAdvice = 1;
let disable = false;
let totalAdvice;

// total count
getAdviceCount().then((data) => {
  totalAdvice = data.count;
  setButtonStatut();
});

leftButton.addEventListener("click", () => {
  if (disable) return;
  disable = true;

  adviceCard.style.transform = "translateX(1000px)";

  getPreviousAdvice().then(async (data) => {
    currentAdvice--;
    showAdvice(data, true);
  });
});

rightButton.addEventListener("click", () => {
  if (disable) return;
  disable = true;

  adviceCard.style.transform = "translateX(-1000px)";

  getNextAdvice().then(async (data) => {
    currentAdvice++;
    showAdvice(data);
  });
});

function setButtonStatut() {
  if (currentAdvice >= totalAdvice) {
    rightButton.disabled = true;
  } else {
    rightButton.disabled = false;
  }
  if (currentAdvice <= 1) {
    leftButton.disabled = true;
  } else {
    leftButton.disabled = false;
  }
}

// show advice with animation and replace text
async function showAdvice(data, reverse = false) {
  setButtonStatut();
  await new Promise((resolve, reject) => {
    setTimeout(() => {
      adviceCard.style.visibility = "hidden";
      adviceCard.style.transform = !reverse
        ? "translateX(1000px)"
        : "translateX(-1000px)";
      text.innerText = data.advice;
      pseudo.innerText = data.pseudo;
      resolve();
    }, 200);
  }).then((resolve) => {
    setTimeout(() => {
      adviceCard.style.transform = "translateX(0px)";
      adviceCard.style.visibility = "visible";
      disable = false;
    }, 300);
  });
}

async function getAdviceCount() {
  const r = await fetch("api/advice/count", {
    method: "GET",
    headers: {
      Accept: "application/json",
    },
  });
  if (r.ok) {
    return r.json();
  }

  throw new Error("Cant get count advice");
}

async function getNextAdvice() {
  const r = await fetch("api/advice/" + (currentAdvice + 1), {
    method: "GET",
    headers: {
      Accept: "application/json",
    },
  });

  if (r.ok) {
    return r.json();
  }
  throw new Error("Cant get advice");
}

async function getPreviousAdvice() {
  const r = await fetch("api/advice/" + (currentAdvice - 1), {
    method: "GET",
    headers: {
      Accept: "application/json",
    },
  });

  if (r.ok) {
    return r.json();
  }
  throw new Error("Cant get advice");
}
