document.addEventListener("DOMContentLoaded", () => {
  const carousels = document.querySelectorAll(".carousel");
  const offset = 150;
  const delay = 6;

  // get all carousels on the page
  carousels.forEach((carousel) => {
    const select = carousel.getElementsByClassName("carousel__select");
    const container = carousel.getElementsByClassName("carousel__container");
    const img = container[0].getElementsByTagName("img");
    const buttonRight = carousel.getElementsByClassName("right-carousel-js");
    const buttonLeft = carousel.getElementsByClassName("left-carousel-js");

    let autoplay = carousel.hasAttribute("autoplay");
    let animationAutoplay;
    let currentImage;

    // click on button right
    if (buttonRight[0]) {
      buttonRight[0].addEventListener("click", () => {
        currentImage++;
        goToImage();
      });
    }

    // click on button left
    if (buttonLeft[0]) {
      buttonLeft[0].addEventListener("click", () => {
        currentImage--;
        goToImage();
      });
    }

    // disable or enable autoplay when users touch carousel
    if (autoplay) {
      startAutoplay();
      carousel.addEventListener("touchstart", () => {
        stopAutoplay();
      });
      carousel.addEventListener("touchend", () => {
        startAutoplay();
      });
    }

    createSelectButton();

    // autoplay carousel
    function startAutoplay() {
      animationAutoplay = setTimeout(() => {
        currentImage++;
        goToImage();
      }, delay * 1000);
    }

    // stop autoplay
    function stopAutoplay() {
      clearTimeout(animationAutoplay);
    }

    // change scroll position to match with image selected
    function goToImage() {
      stopAutoplay();

      if (currentImage >= img.length) {
        currentImage = 0;
      } else if (currentImage <= -1) {
        currentImage = img.length - 1;
      }

      container[0].scrollTo({
        behavior: "smooth",
        left: img[currentImage].offsetLeft,
      });
      if (autoplay) {
        startAutoplay();
      }
    }

    // create button to select image
    function createSelectButton() {
      if (img.length > 1) {
        for (let i = 0; i < img.length; i++) {
          const button = document.createElement("button");

          button.classList.add("carousel__element");
          button.type = "button";
          button.name = `slider-button-${i}`;
          button.ariaLabel = "selectable button for carousel";
          button.id = i;
          select[0].appendChild(button);
        }

        const buttons = select[0].querySelectorAll(".carousel__element");
        currentImage = 0;

        buttons.forEach((button) => {
          detectScrollPosition(button, buttons);
        });

        // listening clicks for buttons
        buttons.forEach((button) => {
          button.addEventListener("click", () => {
            currentImage = button.id;
            goToImage();
          });

          // listening scroll
          container[0].addEventListener("scroll", () =>
            detectScrollPosition(button, buttons)
          );
        });
      }
    }

    // show current active button
    function setActiveButton(buttons, id) {
      buttons.forEach((button) => {
        button.classList.remove("carousel__element--active");
        if (button.id == id) button.classList.add("carousel__element--active");
      });
    }

    // detect scroll position to active correct button
    function detectScrollPosition(button, buttons) {
      if (
        container[0].scrollLeft >= img[button.id].offsetLeft - offset &&
        container[0].scrollLeft <= img[button.id].offsetLeft + offset
      ) {
        setActiveButton(buttons, button.id);
        currentImage = button.id;
      }
    }
  });
});
