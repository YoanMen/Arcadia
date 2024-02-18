const carousels = document.querySelectorAll(".carousel");
const button = `<button id="" type="button" class="carousel__element"></button>`;
const offset = 50;

carousels.forEach((carousel) => {
  const select = carousel.getElementsByClassName("carousel__select");
  const container = carousel.getElementsByClassName("carousel__container");
  const img = container[0].getElementsByTagName("img");

  createSelectButton();

  function createSelectButton() {
    for (let i = 0; i < img.length; i++) {
      const button = document.createElement("button");

      button.classList.add("carousel__element");
      button.type = "button";
      button.id = i;
      select[0].appendChild(button);
      img[i].id = i;
    }

    const buttons = select[0].querySelectorAll(".carousel__element");

    detectScrollPosition(buttons[0], buttons);

    buttons.forEach((button) => {
      button.addEventListener("click", () => {
        console.log("click " + button.id);

        container[0].scrollTo({
          behavior: "smooth",
          left: img[button.id].offsetLeft,
        });
      });

      container[0].addEventListener("scroll", () =>
        detectScrollPosition(button, buttons)
      );
    });
  }

  function setActiveButton(buttons, id) {
    buttons.forEach((button) => {
      button.classList.remove("carousel__element--active");
      if (button.id == id) button.classList.add("carousel__element--active");
    });
  }

  function detectScrollPosition(button, buttons) {
    if (
      container[0].scrollLeft >= img[button.id].offsetLeft - offset &&
      container[0].scrollLeft <= img[button.id].offsetLeft + offset
    ) {
      setActiveButton(buttons, button.id);
    }
  }
});
