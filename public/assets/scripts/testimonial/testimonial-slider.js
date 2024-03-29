function initializeTestimonialSlider() {
  const leftButton = document.getElementById("left-btn");
  const rightButton = document.getElementById("right-btn");
  const adviceCard = document.getElementById("testimonial-card");
  const text = document.getElementById("testimonial-text");
  const pseudo = document.getElementById("testimonial-pseudo");
  const csrf_token = document.head.querySelector(
    'meta[name="csrf-token"]'
  ).content;

  let currentAdvice = 1;
  let disable = false;
  let advices = "";

  // get Advice
  getAdvices().then((data) => {
    advices = data.count;
    if (totalAdvices != 0) {
      setButtonStatut();
    }
  });

  if (leftButton != null) {
    leftButton.addEventListener("click", () => {
      if (disable) return;
      disable = true;

      adviceCard.style.transform = "translateX(1000px)";

      getPreviousAdvice().then(async (data) => {
        currentAdvice--;
        showAdvice(data, true);
      });
    });
  }

  if (rightButton != null) {
    rightButton.addEventListener("click", () => {
      if (disable) return;
      disable = true;

      adviceCard.style.transform = "translateX(-1000px)";

      getNextAdvice().then(async (data) => {
        currentAdvice++;
        showAdvice(data);
      });
    });
  }

  function setButtonStatut() {
    currentAdvice >= totalAdvices
      ? (rightButton.disabled = true)
      : (rightButton.disabled = false);

    currentAdvice <= 1
      ? (leftButton.disabled = true)
      : (leftButton.disabled = false);
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

  async function getAdvices() {
    const r = await fetch("/public/api/advices/approved", {
      method: "GET",
      headers: {
        "X-CSRF-TOKEN": csrf_token,
        Accept: "application/json",
      },
    });
    if (r.status === 200) {
      return r.json();
    } else {
      return { count: 0 };
    }
  }

  async function getNextAdvice() {}

  async function getPreviousAdvice() {}
}

initializeTestimonialSlider();
