document.addEventListener("DOMContentLoaded", initializeTestimonialSlider);

function initializeTestimonialSlider() {
  const leftButton = document.getElementById("left-btn");
  const rightButton = document.getElementById("right-btn");
  const adviceCard = document.getElementById("testimonial-card");
  const text = document.getElementById("testimonial-text");
  const pseudo = document.getElementById("testimonial-pseudo");
  const csrf_token = document
    .querySelector("meta[name='csrf-token']")
    .getAttribute("content");

  let currentAdvice = 1;
  let totalAdvices = 0;
  let advice = null;
  let disable = false;

  // get Advice
  getAdvice(currentAdvice).then((data) => {
    advice = data.advice;
    totalAdvices = data.totalCount;

    loadAdvice();
  });

  if (leftButton != null) {
    leftButton.addEventListener("click", () => {
      if (disable) return;
      disable = true;

      adviceCard.style.transform = "translateX(1000px)";
      currentAdvice--;

      getAdvice(currentAdvice).then((data) => {
        advice = data.advice;
        totalAdvices = data.totalCount;

        loadAdvice(true);
      });
    });
  }

  if (rightButton != null) {
    rightButton.addEventListener("click", () => {
      if (disable) return;
      disable = true;

      adviceCard.style.transform = "translateX(-1000px)";
      currentAdvice++;

      getAdvice(currentAdvice).then((data) => {
        advice = data.advice;
        totalAdvices = data.totalCount;

        loadAdvice(false);
      });
    });
  }

  // set disabled for buttons
  function setButtonStatus() {
    rightButton.disabled = currentAdvice >= totalAdvices;
    leftButton.disabled = currentAdvice <= 1;
  }

  // show advice with animation and replace text
  async function showAdvice(reverse = false) {
    setButtonStatus();
    await new Promise((resolve, reject) => {
      setTimeout(() => {
        adviceCard.style.visibility = "hidden";
        adviceCard.style.transform = !reverse
          ? "translateX(1000px)"
          : "translateX(-1000px)";
        text.innerText = advice.advice;
        pseudo.innerText = advice.pseudo;
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

  function loadAdvice(reverse) {
    if (advice != null) {
      showAdvice(reverse);
    } else {
      adviceCard.outerHTML = `<p class="testimonial__not">Aucun avis</p>`;
    }
  }

  // call back-end to get advice
  async function getAdvice(currentAdvice) {
    const r = await fetch(`/api/advices/approved/${currentAdvice}`, {
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
}
