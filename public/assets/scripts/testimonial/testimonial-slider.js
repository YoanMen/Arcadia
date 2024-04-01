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
  let advices = null;
  let disable = false;

  // get Advice
  getAdvices().then((data) => {
    advices = data.advices;

    console.log(data.advices.length);

    if (advices != null) {
      showAdvice();
    } else {
      adviceCard.outerHTML = `<p class="testimonial__not">Aucun avis pour le moment</p>`;
    }
  });

  if (leftButton != null) {
    leftButton.addEventListener("click", () => {
      if (disable) return;
      disable = true;

      adviceCard.style.transform = "translateX(1000px)";

      currentAdvice--;
      showAdvice(true);
    });
  }

  if (rightButton != null) {
    rightButton.addEventListener("click", () => {
      if (disable) return;
      disable = true;

      adviceCard.style.transform = "translateX(-1000px)";

      currentAdvice++;
      showAdvice();
    });
  }

  // set disabled for buttons
  function setButtonStatut() {
    currentAdvice >= advices.length
      ? (rightButton.disabled = true)
      : (rightButton.disabled = false);

    currentAdvice <= 1
      ? (leftButton.disabled = true)
      : (leftButton.disabled = false);
  }

  // show advice with animation and replace text
  async function showAdvice(reverse = false) {
    setButtonStatut();
    await new Promise((resolve, reject) => {
      setTimeout(() => {
        adviceCard.style.visibility = "hidden";
        adviceCard.style.transform = !reverse
          ? "translateX(1000px)"
          : "translateX(-1000px)";
        text.innerText = advices[currentAdvice - 1].advice;
        pseudo.innerText = advices[currentAdvice - 1].pseudo;
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

  // call back-end to get
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
    }
  }

  async function getNextAdvice() {}

  async function getPreviousAdvice() {}
}

initializeTestimonialSlider();
